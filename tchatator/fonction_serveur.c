/** @file
 * @brief Fonction & commande du serveur
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <unistd.h>
#include <getopt.h>
#include <signal.h>
#include <errno.h>
#include <fcntl.h>
#include <time.h>

#include <arpa/inet.h>

#include <sys/socket.h>

#include <netinet/in.h>

#include <postgresql/libpq-fe.h>

#include "fonction_serveur.h"
#include "const.h"
#include "bdd.h"

int init_socket() {
  int sockfd;

  struct sockaddr_in server_addr;

  // Création du socket
  sockfd = socket(AF_INET, SOCK_STREAM, 0);
  if (sockfd < 0) {
      perror("Erreur lors de la création du socket");
      exit(EXIT_FAILURE);
  }

  // Configuration de l'adresse du serveur
  memset(&server_addr, 0, sizeof(server_addr));
  server_addr.sin_family = AF_INET;
  server_addr.sin_addr.s_addr = INADDR_ANY;
  server_addr.sin_port = htons(PORT);

  // Association du socket à une adresse et un port
  if (bind(sockfd, (struct sockaddr *)&server_addr, sizeof(server_addr)) < 0) {
      perror("Erreur lors du bind");
      close(sockfd);
      exit(EXIT_FAILURE);
  }

  return sockfd;
}

int gestion_commande(PGconn *conn, char *tokken_connexion, char buffer[], int sockfd, struct sockaddr_in client_addr) {
    int running = 1;

    printf("Commande reçu : %s\n", buffer);
    ajouter_logs(conn, tokken_connexion, client_addr, buffer, "info");

    // L'utilisateur doit se connecter pour utiliser le service
    if (strncmp(buffer, COMMANDE_AIDE, strlen(COMMANDE_AIDE)) == 0) {
        afficher_commande_aide(sockfd);

    // Aide de commande
    } else if (strncmp(buffer, COMMANDE_CONNEXION, strlen(COMMANDE_CONNEXION)) == 0) {
        strcpy(tokken_connexion, "467014f1de2617c186a0c35e6d512a2b");

    // Déconnexion client
    } else if (strncmp(buffer, "BYE BYE\r", 8) == 0) {
        const char *response = "Au revoir !\n";
        write(sockfd, response, strlen(response));
        running = 0;

    // Arrêt serveur
    } else if(strncmp(buffer, COMMANDE_STOP, strlen(COMMANDE_STOP)) == 0) {
        const char *response = "Arrêt du serveur.\n";
        printf("%s", response);
        write(sockfd, response, strlen(response));
        running = -1;

    // Commande du service
    } else if (tokken_connexion[0] != '\0') {

        if (strncmp(buffer, "HELLO\r", 6) == 0) {
            const char *response = "COUCOU LES GENS\n";
            write(sockfd, response, strlen(response));

        } else if (strncmp(buffer, "BONJOUR:", 8) == 0) {
            char *name_part = buffer + 8;
            char *newline = strstr(name_part, "\r");
            if (newline) {
                *newline = '\0';
            }

            char *comma = strchr(name_part, ',');
            if (comma) {
                *comma = '\0';
                char *first_name = trim(name_part);
                char *last_name = trim(comma + 1);

                char response[BUFFER_SIZE];
                snprintf(response, sizeof(response), "Bonjour, %s %s !\n", first_name, last_name);
                write(sockfd, response, strlen(response));
            } else {
                const char *response = "Erreur : veuillez inclure une virgule entre le prénom et le nom.\n";
                write(sockfd, response, strlen(response));
            }

        } else if (strncmp(buffer, COMMANDE_MESSAGE, strlen(COMMANDE_MESSAGE)) == 0) {

        } else {
            const char *response = "Commande inconnue.\nCommande d'aide : ";
            write(sockfd, response, strlen(response));
            write(sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
            write(sockfd, "\n", 1);
        }

    } else {
        const char *response = "Vous devez vous connecter pour accéder au SERVICE\nCommande d'aide : ";
        write(sockfd, response, strlen(response));
        write(sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
        write(sockfd, "\n", 1);
    }


    return running;
}

void afficher_commande_aide(int sockfd) {
    char buffer[BUFFER_SIZE];

    // Construire et envoyer chaque partie du message
    snprintf(buffer, sizeof(buffer), "Usage : [Commande]: [params]\n");
    write(sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "Commandes :\n");
    write(sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s <clé api>        Connexion au service\n", COMMANDE_CONNEXION);
    write(sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s                  Déconnexion du service\n", COMMANDE_DECONNECTE);
    write(sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s                  Afficher la version\n", COMMANDE_MESSAGE);
    write(sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s                  Afficher cette aide\n", COMMANDE_AIDE);
    write(sockfd, buffer, strlen(buffer));
}

void afficher_aide() {
    printf("Usage : ./tchatator [options]\n");
    printf("Options :\n");
    printf("  -h, --help        Afficher cette aide\n");
    printf("  -v, --version     Afficher la version\n");
    printf("  -vb, --verbose    Afficher les logs\n");
}

void afficher_logs() {

    char buffer[BUFFER_SIZE];
    ssize_t bytes_read;
    int fd = open(CHEMIN_LOGS ,O_RDONLY);

    if (fd < 0) {
        fprintf(stderr, "Erreur lors de l'ouverture du fichier %s en mode lecture : %s\n", CHEMIN_LOGS, strerror(errno));
        exit(1);
    }

    while ((bytes_read = read(fd, buffer, sizeof(buffer) - 1)) > 0) {
        buffer[bytes_read] = '\0';
        printf("%s", buffer);
    }

    if (bytes_read < 0) {
        perror("Erreur lors de la lecture du fichier");
    }

    close(fd);
}

void ajouter_logs(PGconn *conn, char *tokken_connexion, struct sockaddr_in client_addr, char *commande, char *type) {

    int fd = open(CHEMIN_LOGS, O_WRONLY | O_APPEND);
    if (fd < 0) {
        fprintf(stderr, "Erreur lors de l'ouverture du fichier %s en mode lecture : %s\n", CHEMIN_LOGS, strerror(errno));
        exit(1);
    }

    char buffer[BUFFER_SIZE * 2];
    // récupération de la date
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);
    char date_buff[BUFFER_SIZE / 2];
    snprintf(date_buff, sizeof(date_buff), "%d-%02d-%02d %02d:%02d:%02d", tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, tm.tm_hour + 1, tm.tm_min, tm.tm_sec);

    // Récupérer l'adresse IP du client
    char client_ip[INET_ADDRSTRLEN];

    if (inet_ntop(AF_INET, &client_addr.sin_addr, client_ip, sizeof(client_ip)) == NULL) {
        perror("Erreur lors de la conversion de l'adresse IP");
        exit(1);
    }

    char identiteUser[BUFFER_SIZE / 3];
    if (strcmp(tokken_connexion, "") != 0) {

        const char *paramValues[] = {tokken_connexion};
        char *result = execute_requete(conn, "SELECT idu FROM pact._utilisateur WHERE apikey = $1;", 1, paramValues);
        strcpy(identiteUser, result);

        identiteUser[strlen(identiteUser) - 1] = '\0';
    } else {
        strcpy(identiteUser, "inconnue");
    }

    char info[BUFFER_SIZE];
    snprintf(info, sizeof(info), "%s - %s - %s", identiteUser, client_ip, commande);

    if (strcmp(type, "info") == 0) {
        snprintf(buffer, sizeof(buffer), "%s [INFO] %s", date_buff, info);
    } else if (strcmp(type, "error") == 0) {
        snprintf(buffer, sizeof(buffer), "%s [ERROR] %s", date_buff, info);
    } else if (strcmp(type, "debug") == 0) {
        snprintf(buffer, sizeof(buffer), "%s [DEBUG] %s", date_buff, info);
    } else {
        printf("Type de logs inconnue");
    }

    ssize_t bytes_written = write(fd, buffer, strlen(buffer));

    if (bytes_written < 0) {
        fprintf(stderr, "Erreur lors de l'écriture dans le fichier %s : %s\n", CHEMIN_LOGS, strerror(errno));
        close(fd);
        exit(1);
    }

    close(fd);
}

void gestion_option(int argc, char *argv[]) {
    int opt;

    // Définition des options longues
    static struct option long_options[] = {
        {"help",    no_argument,       0, 'h'},
        {"version", no_argument,       0, 'v'},
        {"verbose",  no_argument, 0, 'b'},
        {0, 0, 0, 0}
    };

    while ((opt = getopt_long(argc, argv, "hvb", long_options, NULL)) != -1) {
        switch (opt) {
            case 'h': // Option -h ou --help
                afficher_aide();
                break;
            case 'v': // Option -v ou --version
                printf("Tchatator (The Void) - Version 1.0.0\n");
                break;
            case 'b': // Option -b ou --verbose
                afficher_logs();
                break;
            case '?':  // Option inconnue
                printf("Commande inconnue, --help pour voir les options\n");
                break;
        }
    }
}

void killChld(int sig, siginfo_t *info, void *context) {
    if (sig == SIGUSR1) {
        printf("Le processus enfant a signalé une fin avec -1, arrêt du serveur.\n");
        kill(getpid(), SIGKILL);  // Envoie SIGKILL au processus parent pour l'arrêter
    }
}

// trim(char[]) comme en php
char *trim(char *str) {
    char *end;

    // Trim leading space
    while (*str == ' ' || *str == '\t' || *str == '\n' || *str == '\r') {
        str++;
    }

    // All spaces?
    if (*str == 0) {
        return str;
    }

    end = str + strlen(str) - 1;
    while (end > str && (*end == ' ' || *end == '\t' || *end == '\n' || *end == '\r')) {
        end--;
    }

    *(end + 1) = '\0';

    return str;
}