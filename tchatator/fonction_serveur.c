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

#include <json-c/json.h>

#include "outils.h"
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

void gestion_commande(PGconn *conn, tExplodeRes requete, tClient *utilisateur) {

    ajouter_logs(conn, *utilisateur, requete.elements[0] , "info");

    // L'utilisateur doit se connecter pour utiliser le service
    if (strcmp(requete.elements[0], COMMANDE_AIDE) == 0) {
        //afficher_commande_aide(*utilisateur);

    // Aide de commande
    } else if (strcmp(requete.elements[0], COMMANDE_CONNEXION) == 0) {
        connexion(conn, utilisateur, requete);

    // Historique
    } else if (strcmp(requete.elements[0], COMMANDE_HISTORIQUE) == 0) {
        //afficheHistorique(conn, buffer + strlen(COMMANDE_HISTORIQUE));

    // Arrêt serveur
    } else if(strcmp(requete.elements[0], COMMANDE_STOP) == 0) {
        // tue le processus serveur
        kill(getppid(), SIGUSR1);
    
    // Déconnexion
    } else if(strcmp(requete.elements[0], COMMANDE_DECONNECTE) == 0) {

    // Commande Hello
    } else if (strncmp(requete.elements[0], "HELLO\r", 6) == 0) {
        const char *response = "COUCOU LES GENS\n";
        write(utilisateur->sockfd, response, strlen(response));

    // Commande MSG
    } else if (strcmp(requete.elements[0], COMMANDE_MESSAGE) == 0) {
        //printf("%s\n", buffer);
        //saisit_message(conn, utilisateur, buffer + strlen(COMMANDE_MESSAGE));
    
    // Commande Inconnue
    } else {
        const char *response = "Commande inconnue.\nCommande d'aide : ";
        write(utilisateur->sockfd, response, strlen(response));
        write(utilisateur->sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
        write(utilisateur->sockfd, "\n", 1);
    }
}

void afficheHistorique(PGconn *conn, char tokken[]) {
    if (conn == NULL || PQstatus(conn) != CONNECTION_OK) {
        fprintf(stderr, "Erreur de connexion à la base de données : %s\n", PQerrorMessage(conn));
        return;
    }
    
    char requete[BUFFER_SIZE * 2];
    snprintf(requete, sizeof(requete), 
             "SELECT vueMessages.idMessage, vueMessages.dateMessage, vueMessages.contenuMessage, "
             "vueMessages.nomExpediteur, vueMessages.nomReceveur "
             "FROM pact.vueMessages "
             "JOIN pact._utilisateur ON utilisateur.idU = vueMessages.idReceveur OR utilisateur.idU = vueMessages.idExpediteur "
             "WHERE utilisateur.tokken = '%s' "
             "ORDER BY vueMessages.dateMessage DESC;", 
             tokken);
    
    PGresult *res = PQexec(conn, requete);
    
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
        PQclear(res);
        return;
    }
    
    int nrows = PQntuples(res);
    if (nrows == 0) {
        printf("Aucun message trouvé.\n");
    } else {
        printf("Historique des messages :\n");
        for (int i = 0; i < nrows; i++) {
            printf("\"emetteur\": \"%s\",\n", PQgetvalue(res, i, 3));
            printf("\"destinataire\": \"%s\",\n", PQgetvalue(res, i, 4));
            printf("\"sens\": \"%s -> %s\"\n", PQgetvalue(res, i, 3), PQgetvalue(res, i, 3));
            printf("\"identifiant\": %s,\n", PQgetvalue(res, i, 0));
            printf("\"horodatage\": \"%s\",\n", PQgetvalue(res, i, 1));
            printf("\"message\": {\n");
            printf("\"contenue\": \"%s\",\n", PQgetvalue(res, i, 2));
            printf("\"taille_message\": %lu\n", strlen(PQgetvalue(res, i, 2)));
        }
    }
    
    PQclear(res);
}
void connexion(PGconn *conn, tClient *utilisateur, tExplodeRes requete) {

    char requeteUpdate[150];
    char genTokken[20];

    if (nombre_argument_requis(conn, *utilisateur, requete, 1)) {
        struct json_object *json_obj = json_object_new_object();
        srand(time(NULL));
        genere_tokken(genTokken);
        
        json_object_object_add(json_obj, "tokken", json_object_new_string(genTokken));
        sprintf(requeteUpdate, "UPDATE pact._utilisateur SET tokken = '%s' WHERE idu = %d;", genTokken, atoi(utilisateur->identiteUser));
        updateBDD(conn, requeteUpdate);

        json_object_object_add(json_obj, "statut", json_object_new_string(REP_200));
        json_object_object_add(json_obj, "tokken", json_object_new_string(genTokken));
        send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "info");
    }
}

void genere_tokken(char *key) {
    //On créé une chaine avec tous les caractères possibles
    const char chaine[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
    int taille_chaine = sizeof(chaine) - 1; 

    // Initialiseation du générateur de nombres aléatoires
    for (int i = 0; i < TOKKEN_LENGTH; i++) {
        key[i] = chaine[rand() % taille_chaine];  // Choix d'un caractère au hasard dans la chaine
    }
    key[TOKKEN_LENGTH] = '\0';  // Ajouter une fin de chaine à la fin du tokken
}

void saisit_message(PGconn *conn, tClient *utilisateur, char buffer[]) {

    tExplodeRes result = explode(buffer, "|");

    if (result.nbElement != 3) {
        // Manque d'argument
        if (result.nbElement < 3) {
            //envoie_erreur(conn, *utilisateur, REP_400_MISSING_ARGS);
        // Trop d'argument
        } else {
            //envoie_erreur(conn, *utilisateur, REP_400_TOO_MANY_ARGS);
        }

    } else {
        for (int i = 0; i < result.nbElement; i++) {
            printf("elements[%d]: %s\n", i, result.elements[i]);
        }
    }

    freeExplodeResult(&result);
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

    snprintf(buffer, sizeof(buffer), "  %s <tokken> | <destinataire_api> | <message>  Saisir un message\n", COMMANDE_MESSAGE);
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

void ajouter_logs(PGconn *conn, tClient utilisateur, const char *message, const char *type) {
    int fd = open(CHEMIN_LOGS, O_WRONLY | O_APPEND | O_CREAT, 0644);
    if (fd < 0) {
        fprintf(stderr, "Erreur lors de l'ouverture du fichier %s en mode lecture : %s\n", CHEMIN_LOGS, strerror(errno));
        exit(1);
    }

    char buffer[BUFFER_SIZE * 2];
    
    static int id_log = 1;
    int log_id = id_log++;

    // Récupération de la date et de l'heure
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);
    char date_buff[BUFFER_SIZE / 2];
    snprintf(date_buff, sizeof(date_buff), "%d-%02d-%02d %02d:%02d:%02d", tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, tm.tm_hour, tm.tm_min, tm.tm_sec);

    // Information sur l'utilisateur et la commande
    char info[BUFFER_SIZE];
    snprintf(info, sizeof(info), "%s - %s - %s", utilisateur.identiteUser, utilisateur.client_ip, message);

    if (strcmp(type, "info") == 0) {
        snprintf(buffer, sizeof(buffer), "%d - %s - [INFO] - %s", log_id, date_buff, info);
    } else if (strcmp(type, "error") == 0) {
        snprintf(buffer, sizeof(buffer), "%d - %s - [ERROR] - %s", log_id, date_buff, info);
    } else if (strcmp(type, "debug") == 0) {
        snprintf(buffer, sizeof(buffer), "%d - %s - [DEBUG] - %s", log_id, date_buff, info);
    } else {
        snprintf(buffer, sizeof(buffer), "%d - %s - [UNKNOWN TYPE] - %s", log_id, date_buff, info);
    }

    // Écriture dans le fichier de log
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

tExplodeRes init_argument(PGconn *conn, tClient *utilisateur, char buffer[]) {
    tExplodeRes res;
    res.nbElement = 0;
    res.elements = malloc(sizeof(char *));
    
    if (!res.elements) {
        printf("Erreur d'allocation mémoire.\n");
        exit(EXIT_FAILURE);
    }

    char commande[BUFFER_SIZE];
    int n = 0;
    while (buffer[n] != ':' && buffer[n] != '\0' && n < BUFFER_SIZE - 1) {
        commande[n] = buffer[n];
        n++;
    }
    commande[n] = '\0';

    res.elements[0] = strdup(commande);
    if (!res.elements[0]) {
        printf("Erreur d'allocation mémoire.\n");
        free(res.elements);
        exit(EXIT_FAILURE);
    }
    res.nbElement++;

    buffer = buffer + strlen(commande) + 1;
    if (!est_commande(commande)) {
        struct json_object *json_obj = json_object_new_object();
        json_object_object_add(json_obj, "statut", json_object_new_string(REP_400_UNKNOWN_PARAMETER));
        send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
        return res;  // Retourne ici si la commande est invalide.
    }

    tExplodeRes tmp = explode(buffer, "|");
    concat_struct(&res, &tmp);

    if (res.nbElement > 1) {
        // Utilisation d'une requête préparée
        const char *query = 
            "SELECT u.idu, "
            "CASE "
                "WHEN m.idu IS NOT NULL THEN 'membre' "
                "WHEN p.idu IS NOT NULL THEN 'pro' "
                "WHEN a.idu IS NOT NULL THEN 'admin' "
                "ELSE 'inconnue' "
            "END AS statut, "
            "CASE "
                "WHEN u.tokken = $1 THEN 'tokken' "
                "WHEN u.apikey = $1 THEN 'apikey' "
                "ELSE 'inconnu' "
            "END AS source "
            "FROM pact._utilisateur u "
            "LEFT JOIN pact._membre m ON u.idu = m.idu "
            "LEFT JOIN pact._pro p ON u.idu = p.idu "
            "LEFT JOIN pact._admin a ON u.idu = a.idu "
            "WHERE (u.tokken = $1 OR u.apikey = $1);";

        // Préparer la requête avec le paramètre
        PGresult *res_prepared = PQprepare(conn, "login_query", query, 1, NULL);
        if (PQresultStatus(res_prepared) != PGRES_COMMAND_OK) {
            fprintf(stderr, "Erreur lors de la préparation de la requête : %s\n", PQerrorMessage(conn));
            PQclear(res_prepared);
            return res;  // Retourne ici si la préparation échoue.
        }
        PQclear(res_prepared);

        // Paramètres pour la requête préparée
        const char *param_values[1];
        param_values[0] = res.elements[1];  // Assumant que le token est dans res.elements[1]

        // Exécuter la requête préparée
        PGresult *pg_res = PQexecPrepared(conn, "login_query", 1, param_values, NULL, NULL, 0);
    
        if (PQresultStatus(pg_res) != PGRES_TUPLES_OK) {
            fprintf(stderr, "Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
            PQclear(pg_res);
            return res;  // Retourne ici en cas d'erreur dans la requête.
        }
        
        int nrows = PQntuples(pg_res);
        if (nrows > 0) {
            // Récupérer les résultats de la requête
            strcpy(utilisateur->identiteUser, PQgetvalue(pg_res, 0, 0));
            strcpy(utilisateur->type, PQgetvalue(pg_res, 0, 1));
            strcpy(utilisateur->tokken_connexion, res.elements[1]);
            utilisateur->est_connecte = (strcmp(PQgetvalue(pg_res, 0, 2), "tokken") == 0);
        }
        
        PQclear(pg_res);
    }

    return res;
}

bool est_commande(char buffer[]) {
    // Listes de commandes
    const char *commandes[] = {
        COMMANDE_CONNEXION,
        COMMANDE_MESSAGE,
        COMMANDE_DECONNECTE,
        COMMANDE_AIDE,
        COMMANDE_STOP,
        COMMANDE_HISTORIQUE
    };

    int nbCommandes = sizeof(commandes) / sizeof(commandes[0]);

    // Vérification de l'existence de la commande
    for (int i = 0; i < nbCommandes; i++) {
        if (strcmp(buffer, commandes[i]) == 0) {
            return true;
        }
    }

    return false;
}