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

#include <sys/socket.h>

#include <netinet/in.h>

#include "fonction_serveur.h"
#include "const.h"

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

int gestion_commande(char buffer[], int sockfd) {
  int running = 1;

  printf("Commande reçu : %s\n", buffer);

  if (strncmp(buffer, "HELLO\r", 6) == 0) {
      const char *response = "COUCOU LES GENS\n";
      write(sockfd, response, strlen(response));

  } else if (strncmp(buffer, "BYE BYE\r", 8) == 0) {
      const char *response = "Au revoir !\n";
      write(sockfd, response, strlen(response));
      running = 0;

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

  } else if (strncmp(buffer, COMMANDE_CONNEXION, strlen(COMMANDE_CONNEXION)) == 0) {
    
  } else if(strncmp(buffer, COMMANDE_STOP, strlen(COMMANDE_STOP)) == 0) {
    const char *response = "Arrêt du serveur.\n";
    printf("%s", response);
    write(sockfd, response, strlen(response));
    running = -1;
  } else {
      const char *response = "Commande inconnue.\n";
      write(sockfd, response, strlen(response));
  }

  return running;
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
        perror("Erreur lors de l'ouverture du fichier");
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

void ajouter_logs(char commande[]) {

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