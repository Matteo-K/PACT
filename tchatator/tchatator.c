#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>

#include "bdd.c"
#include "function_serveur.h"
#include "const.h"

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

int main() {
    int sockfd = init_socket();

    // Mise en écoute
    if (listen(sockfd, 1) < 0) {
        perror("Erreur lors de l'écoute");
        close(sockfd);
        exit(EXIT_FAILURE);
    }

    printf("Serveur en attente de connexions sur le port %d...\n", PORT);

    // Acceptation d'une connexion
    struct sockaddr_in client_addr;
    socklen_t client_len = sizeof(client_addr);
    int newsockfd = accept(sockfd, (struct sockaddr *)&client_addr, &client_len);
    if (newsockfd < 0) {
        perror("Erreur lors de l'acceptation de la connexion");
        close(sockfd);
        exit(EXIT_FAILURE);
    }

    printf("Connexion acceptée.\n");

    char buffer[256];
    int running = 1;

    while (running) {
    
      // Lecture de commande
      memset(buffer, 0, sizeof(buffer));
      int n = read(newsockfd, buffer, sizeof(buffer) - 1);
      if (n < 0) {
        perror("Erreur lors de la lecture du message");
        break;
      }

      running = gestion_commande(buffer, newsockfd);
    }

    // Fermeture des sockets
    close(newsockfd);
    close(sockfd);

    return 0;
}
