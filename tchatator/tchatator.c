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

#include "bdd.h"
#include "fonction_serveur.h"
#include "const.h"


pid_t liste_attente[NB_CONNEXION_MAX];
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

    char buffer[BUFFER_SIZE];

    int running = 1;

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

        char tokken_connexion[BUFFER_SIZE] = "";

        while (running > 0) {
          // Lecture de commande
          memset(buffer, 0, sizeof(buffer));
          int n = read(newsockfd, buffer, sizeof(buffer) - 1);
          if (n < 0) {
            perror("Erreur lors de la lecture du message");
            break;
          }

          running = gestion_commande(tokken_connexion, buffer, newsockfd);
        }

        close(newsockfd);

        if (running == -1) {
          kill(getppid(), SIGUSR1);
        }

        exit(running);

      } else {
        liste_attente[nb_liste_attente] = pid;
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
