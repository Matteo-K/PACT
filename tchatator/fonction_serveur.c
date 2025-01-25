/** @file
 * @brief Fonction & commande du serveur
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <unistd.h>
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

          char response[256];
          snprintf(response, sizeof(response), "Bonjour, %s %s !\n", first_name, last_name);
          write(sockfd, response, strlen(response));
      } else {
          const char *response = "Erreur : veuillez inclure une virgule entre le prénom et le nom.\n";
          write(sockfd, response, strlen(response));
      }

  } else if (strncmp(buffer, COMMANDE_CONNEXION, strlen(COMMANDE_CONNEXION)) == 0) {
    
  } else {
      const char *response = "Commande inconnue.\n";
      write(sockfd, response, strlen(response));
  }

  return running;
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