/** @file
 * @brief Outils de traitement
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

#include <json-c/json.h>

#include "fonction_serveur.h"
#include "const.h"
#include "bdd.h"


void killChld(int sig, siginfo_t *info, void *context) {
    if (sig == SIGUSR1) {
        printf("Le processus enfant %d a été signalé.\n arrêt du serveur.\n", info->si_pid);
        kill(getpid(), SIGKILL);  // Envoie SIGKILL au processus parent pour l'arrêter
    }
}

void send_json_request(PGconn *conn, tClient utilisateur, const char *json_body, char type[]) {

    ajouter_logs(conn, utilisateur, *json_body, type);

    // Construire la requête HTTP (avec JSON dans le corps)
    char request[BUFFER_SIZE * 4];
    sprintf(request,
            "GET /login HTTP/1.1\r\n"
            "Host: %s\r\n"
            "Content-Type: application/json\r\n"
            "Content-Length: %lu\r\n"
            "\r\n"
            "%s\r\n", 
            SERVEUR, strlen(json_body), json_body);

    // Envoyer la requête
    if (send(utilisateur.sockfd, request, strlen(request), 0) < 0) {
        perror("Send failed");
        return;
    }
}

// trim(char[]) comme en php
char *trim(char *str) {
    char *end;
    while (*str == ' ' || *str == '\t' || *str == '\n' || *str == '\r') {
        str++;
    }

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

void concat_struct(tExplodeRes *struct1, tExplodeRes *struct2) {
    int taille = struct1->nbElement + struct2->nbElement;

    struct1->elements = realloc(struct1->elements, taille * sizeof(char *));
    if (!struct1->elements) {
        printf("Erreur de réallocation de mémoire.\n");
        return;
    }

    for (int i = 0; i < struct2->nbElement; i++) {
        struct1->elements[struct1->nbElement + i] = strdup(struct2->elements[i]);
    }

    struct1->nbElement = taille;
}

tExplodeRes explode(char buffer[], const char *separateur) {
    tExplodeRes result;
    result.nbElement = 0;
    result.elements = NULL;
    
    char *tempBuffer = strdup(buffer);
    if (!tempBuffer) {
        perror("Erreur d'allocation mémoire");
        exit(EXIT_FAILURE);
    }
    
    char *token = strtok(tempBuffer, separateur);
    while (token != NULL) {

        token = trim(token);

        // Allouage dynamique de la mémoire
        result.elements = realloc(result.elements, (result.nbElement + 1) * sizeof(char *));
        if (!result.elements) {
            perror("Erreur de réallocation mémoire");
            free(tempBuffer);
            exit(EXIT_FAILURE);
        }

        // Ajout d'un élément
        result.elements[result.nbElement] = strdup(token);
        if (!result.elements[result.nbElement]) {
            perror("Erreur d'allocation mémoire");
            free(tempBuffer);
            exit(EXIT_FAILURE);
        }

        result.nbElement++;
        token = strtok(NULL, separateur);
    }

    free(tempBuffer);
    return result;
}

void freeExplodeResult(tExplodeRes *result) {
    for (int i = 0; i < result->nbElement; i++) {
        free(result->elements[i]);
    }
    free(result->elements);
}

void afficherClient(tClient utilisateur) {
    printf("=== Informations du client ===\n");
    printf("Identité       : %s\n", utilisateur.identiteUser);
    printf("Tokken         : %s\n", utilisateur.tokken_connexion);
    printf("Adresse IP     : %s\n", utilisateur.client_ip);
    printf("Type           : %s\n", utilisateur.type);
    printf("Socket         : %d\n", utilisateur.sockfd);
    printf("Est connecté ? : %s\n", utilisateur.est_connecte ? "Oui" : "Non");
}

void afficherTExplodeRes(tExplodeRes structure) {
  for (int i = 0; i < structure.nbElement; i++) {
    printf("elements[%d]: %s\n", i, structure.elements[i]);
  }
}