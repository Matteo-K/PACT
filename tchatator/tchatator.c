#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <signal.h>
#include <errno.h>

#include <sys/types.h>
#include <sys/socket.h>
#include <sys/wait.h>

#include <netinet/in.h>

#include <postgresql/libpq-fe.h>

#include <json-c/json.h>

#include "fonction_serveur.h"
#include "outils.h"
#include "bdd.h"
#include "const.h"


int nb_liste_attente = 0;

int main(int argc, char *argv[]) {

  if (argc > 1) {
    gestion_option(argc, argv);
  } else {
    // signaux
    struct sigaction sa;
    memset(&sa, 0, sizeof(sa));
    sa.sa_sigaction = killChld;
    sa.sa_flags = SA_SIGINFO;
    sigaction(SIGUSR1, &sa, NULL);

    int sockfd = init_socket();

    PGconn *conn = init_bdd();

    // Mise en écoute
    if (listen(sockfd, 1) < 0) {
      perror("Erreur lors de l'écoute");
      close(sockfd);
      exit(EXIT_FAILURE);
    }

    printf("Serveur en attente de connexions sur le port %d...\n", PORT);

    printf("Connexion acceptée.\n");

    while (1) {
      // Acceptation d'une connexion
      struct sockaddr_in client_addr;
      socklen_t client_len = sizeof(client_addr);
      int newsockfd = accept(sockfd, (struct sockaddr *)&client_addr, &client_len);
      if (newsockfd < 0) {
        perror("Erreur lors de l'acceptation de la connexion");
        close(sockfd);
        exit(EXIT_FAILURE);
      }

      pid_t pid = fork();
      if (pid < 0) {
        perror("Erreur lors du fork");
        close(newsockfd);
        continue;
      }

      if (pid == 0) {

        // Récupérer l'adresse IP du client
        char client_ip[INET_ADDRSTRLEN];

        if (inet_ntop(AF_INET, &client_addr.sin_addr, client_ip, sizeof(client_ip)) == NULL) {
            perror("Erreur lors de la conversion de l'adresse IP");
            exit(1);
        }

        tClient utilisateur = {
          .identiteUser = "inconnue",
          .tokken_connexion = "",
          .client_ip = "",
          .type = "",
          .sockfd = newsockfd,
          .est_connecte = false
        };

        // Initialise l'ip
        strcpy(utilisateur.client_ip, client_ip);

        // Lecture de commande
        char buffer[BUFFER_SIZE];
        memset(buffer, 0, sizeof(buffer));
        int n = read(utilisateur.sockfd, buffer, sizeof(buffer) - 1);
        if (n < 0) {
          perror("Erreur lors de la lecture du message");
          break;
        }

        tExplodeRes argument = init_argument(conn, &utilisateur, buffer);

        afficherTExplodeRes(argument);
        afficherClient(utilisateur);

        gestion_commande(conn, argument, &utilisateur);
        freeExplodeResult(&argument);
        close(utilisateur.sockfd);
        _exit(0);

      } else {
        nb_liste_attente++;
        printf("### Connexion reçu N°%d\n", nb_liste_attente);

        close(newsockfd);
      }
    }

    // Fermeture des sockets
    PQfinish(conn);
    close(sockfd);
  }
  return 0;
}
