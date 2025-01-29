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

int gestion_commande(PGconn *conn, char buffer[], tClient *utilisateur) {
    int running = 1;

    printf("Commande reçu : %s\n", buffer);
    ajouter_logs(conn, *utilisateur, buffer, "info");

    // L'utilisateur doit se connecter pour utiliser le service
    if (strncmp(buffer, COMMANDE_AIDE, strlen(COMMANDE_AIDE)) == 0) {
        afficher_commande_aide(*utilisateur);

    // Aide de commande
    } else if (strncmp(buffer, COMMANDE_CONNEXION, strlen(COMMANDE_CONNEXION)) == 0) {
        strcpy(utilisateur->tokken_connexion, "467014f1de2617c186a0c35e6d512a2b");

    // Déconnexion client
    } else if (strncmp(buffer, "BYE BYE\r", 8) == 0) {
        const char *response = "Au revoir !\n";
        write(utilisateur->sockfd, response, strlen(response));
        running = 0;

    // Arrêt serveur
    } else if(strncmp(buffer, COMMANDE_STOP, strlen(COMMANDE_STOP)) == 0) {
        const char *response = "Arrêt du serveur.\n";
        printf("%s", response);
        write(utilisateur->sockfd, response, strlen(response));
        running = -1;

    // Commande du service
    } else if (utilisateur->tokken_connexion[0] != '\0') {

        if (strncmp(buffer, "HELLO\r", 6) == 0) {
            const char *response = "COUCOU LES GENS\n";
            write(utilisateur->sockfd, response, strlen(response));

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
                write(utilisateur->sockfd, response, strlen(response));
            } else {
                const char *response = "Erreur : veuillez inclure une virgule entre le prénom et le nom.\n";
                write(utilisateur->sockfd, response, strlen(response));
            }

        } else if (strncmp(buffer, COMMANDE_MESSAGE, strlen(COMMANDE_MESSAGE)) == 0) {
            saisit_message(conn, *utilisateur, buffer);
        } else {
            const char *response = "Commande inconnue.\nCommande d'aide : ";
            write(utilisateur->sockfd, response, strlen(response));
            write(utilisateur->sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
            write(utilisateur->sockfd, "\n", 1);
        }

    } else {
        const char *response = "Vous devez vous authentifier pour accéder au SERVICE\nCommande d'aide : ";
        write(utilisateur->sockfd, response, strlen(response));
        write(utilisateur->sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
        write(utilisateur->sockfd, "\n", 1);
    }


    return running;
}

int connexion(PGconn *conn, tClient *utilisateur, char buffer[]) {

    char cleAPI[50];
    char requete[125];
    int idu;
    char requeteMembre[150];
    char requetePro[150];
    char requeteAdmin[150];

    printf("Bienvenue sur le service de discussion Tchatator \nEntrez votre clé API : ");
    scanf("%s", cleAPI);

    sprintf(requete, "SELECT idu FROM pact._utilisateur WHERE apikey = '%s';", cleAPI);

    idu = trouveAPI(conn, requete);

    if(idu != -1){
        printf("Connexion réussie, utilisateur n°%d", idu);
        *utilisateur->identiteUser = idu;
        strcpy(utilisateur->tokken_connexion, cleAPI);

        sprintf(requeteMembre, "SELECT idu FROM pact._admin WHERE idu = %d;", idu);
        sprintf(requetePro, "SELECT idu FROM pact._admin WHERE idu = %d;", idu);
        sprintf(requeteAdmin, "SELECT idu FROM pact._admin WHERE idu = %d;", idu);

        if (trouveAPI(conn, requeteMembre) > 0){
            strcpy(utilisateur->type, TYPE_MEMBRE);

        } else if(trouveAPI(conn, requetePro) > 0){
            strcpy(utilisateur->type, TYPE_PRO);

        } else if (trouveAPI(conn, requeteAdmin) > 0){
            strcpy(utilisateur->type, TYPE_ADMIN);

        } else{
            strcpy(utilisateur->type, "inconnu");

        }

        return idu;
    } else{
        printf("Clé API inexistante, veuillez la consulter sur le site PACT, dans la section 'Mon compte'");
    }
    return -1;
}

void saisit_message(PGconn *conn, tClient utilisateur, char buffer[]) {
    printf("buffer : %s\n", buffer);
    char *name_part = buffer + strlen(COMMANDE_MESSAGE);

    tExplodeRes result = explode(name_part, "|");

    for (int i = 0; i < result.nbElement; i++) {
        printf("elements[%d]: %s\n", i, result.elements[i]);
    }
}

void renvoie_erreur(int code) {
    char err[BUFFER_SIZE];
    switch (code) {
        case 200:
            strcpy(err, "200/OK");
    }
}

void afficher_commande_aide(tClient utilisateur) {
    char buffer[BUFFER_SIZE];

    // Construire et envoyer chaque partie du message
    snprintf(buffer, sizeof(buffer), "Usage : [Commande]: [params]\n");
    write(utilisateur.sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "Commandes :\n");
    write(utilisateur.sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s <clé api>                   Connexion au service\n", COMMANDE_CONNEXION);
    write(utilisateur.sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s                             Déconnexion du service\n", COMMANDE_DECONNECTE);
    write(utilisateur.sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s <destinataire> | <message>  Afficher la version\n", COMMANDE_MESSAGE);
    write(utilisateur.sockfd, buffer, strlen(buffer));

    snprintf(buffer, sizeof(buffer), "  %s                  Afficher cette aide\n", COMMANDE_AIDE);
    write(utilisateur.sockfd, buffer, strlen(buffer));
}

void afficher_aide() {
    printf("Usage : ./tchatator [options]\n");
    printf("Options :\n");
    printf("  -h, --help        Afficher cette aide\n");
    printf("  -v, --version     Afficher la version\n");
    printf("  -b, --verbose    Afficher les logs\n");
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

void ajouter_logs(PGconn *conn, tClient utilisateur, char *commande, char *type) {

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

    char info[BUFFER_SIZE];
    snprintf(info, sizeof(info), "%s - %s - %s", utilisateur.identiteUser, utilisateur.client_ip, commande);

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

void send_json_request(int sock, const char *json_body) {
    char buffer[BUFFER_SIZE * 2];

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
    if (send(sock, request, strlen(request), 0) < 0) {
        perror("Send failed");
        return;
    }

    // Lire la réponse du serveur
    int bytes_received = recv(sock, buffer, sizeof(buffer) - 1, 0);
    if (bytes_received < 0) {
        perror("Receive failed");
    } else {
        buffer[bytes_received] = '\0';  // Ajouter un terminator pour la chaîne
        printf("Server response:\n%s\n", buffer);
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

tExplodeRes explode(char *buffer, char *separateur) {

    // Initialisation des résultats
    tExplodeRes result;
    result.elements = malloc(10 * sizeof(char *));
    result.nbElement = 0;
    
    char *element = trim(strtok(buffer, separateur));

    while (element != NULL) {

        result.elements[result.nbElement] = element;
        result.nbElement++;

        // Récupérer le prochain élément
        element = trim(strtok(NULL, separateur));
    }

    return result;
}