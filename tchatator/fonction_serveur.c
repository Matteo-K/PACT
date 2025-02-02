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
        afficher_commande_aide(conn, requete, *utilisateur);

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
        saisit_message(conn, utilisateur, requete);
    
    // Commande Inconnue
    } else {
        const char *response = "Commande inconnue.\nCommande d'aide : ";
        write(utilisateur->sockfd, response, strlen(response));
        write(utilisateur->sockfd, COMMANDE_AIDE, strlen(COMMANDE_AIDE));
        write(utilisateur->sockfd, "\n", 1);
    }
}

void afficheHistorique(PGconn *conn, char tokken[]) {

    tokken = trim(tokken);
    
    char requete[BUFFER_SIZE * 2];
    snprintf(requete, sizeof(requete), 
             "SELECT vueMessages.idMessage, vueMessages.dateMessage, vueMessages.contenuMessage, "
             "vueMessages.nomExpediteur, vueMessages.nomReceveur "
             "FROM pact.vueMessages "
             "JOIN pact._utilisateur ON _utilisateur.idU = vueMessages.idReceveur OR _utilisateur.idU = vueMessages.idExpediteur "
             "WHERE _utilisateur.tokken = '%s' "
             "ORDER BY vueMessages.dateMessage DESC;", 
             tokken);
    
    PGresult *res = PQexec(conn, requete);
    
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

    char requeteAPI[150];
    int idu;
    char requeteUpdate[150];
    char genTokken[20];
    char buffer[20];

    if (nombre_argument_requis(conn, *utilisateur, requete, 1)) {
        
        sprintf(requeteAPI, "SELECT idu FROM pact._utilisateur WHERE apikey = '%s';", trim(requete.elements[requete.nbElement]));
        idu = trouveAPI(conn, requeteAPI);
        struct json_object *json_obj = json_object_new_object();
        if(idu != -1){
        
            srand(time(NULL));
            genere_tokken(genTokken);
            
            json_object_object_add(json_obj, "tokken", json_object_new_string(genTokken));
            sprintf(requeteUpdate, "UPDATE pact._utilisateur SET tokken = '%s' WHERE idu = %d;", genTokken, idu);
            updateBDD(conn, requeteUpdate);

            sprintf(buffer, "%d", idu); 
            strcpy(utilisateur->identiteUser, buffer);

            json_object_object_add(json_obj, "statut", json_object_new_string(REP_200));
            json_object_object_add(json_obj, "tokken", json_object_new_string(genTokken));
            send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "info");
        }

        else{
            json_object_object_add(json_obj, "statut", json_object_new_string(REP_401));
        }
        
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

void saisit_message(PGconn *conn, tClient *utilisateur, tExplodeRes requete) {

    if (nombre_argument_requis(conn, *utilisateur, requete, 3)) {

        struct json_object *json_obj = json_object_new_object();

        // Vérification du type utilisateur
        if (!(strcmp(utilisateur->type, TYPE_MEMBRE) == 0 || strcmp(utilisateur->type, TYPE_PRO) == 0)) {
            json_object_object_add(json_obj, "statut", json_object_new_string(REP_403_UNAUTHORIZED_USE));
            send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
            return;
        }

        // Vérification de la longueur du message
        if (strlen(requete.elements[3]) > TAILLE_MAX_MSG) {
            json_object_object_add(json_obj, "statut", json_object_new_string(REP_416));
            send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
            return;
        }

        // Vérification du destinataire
        const char *query =
            "SELECT u.idu, "
            "CASE "
                "WHEN m.idu IS NOT NULL THEN 'membre' "
                "WHEN p.idu IS NOT NULL THEN 'pro' "
                "WHEN a.idu IS NOT NULL THEN 'admin' "
                "ELSE 'inconnue' "
            "END AS statut "
            "FROM pact._utilisateur u "
            "LEFT JOIN pact._membre m ON u.idu = m.idu "
            "LEFT JOIN pact._pro p ON u.idu = p.idu "
            "LEFT JOIN pact._admin a ON u.idu = a.idu "
            "WHERE u.apikey = $1;";

        const char *param_values[1] = { requete.elements[2] };
        
        PGresult *pg_res = PQexecParams(conn, query, 1, NULL, param_values, NULL, NULL, 0);
        
        if (PQresultStatus(pg_res) != PGRES_TUPLES_OK) {
            fprintf(stderr, "Erreur requête: %s\n", PQerrorMessage(conn));
            PQclear(pg_res);
            json_object_object_add(json_obj, "statut", json_object_new_string(REP_500));
            send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
            return;
        }

        if (PQntuples(pg_res) > 0) {
            char idu_dest[BUFFER_SIZE / 4];
            char type_dest[BUFFER_SIZE];
            strcpy(idu_dest, PQgetvalue(pg_res, 0, 0));
            strcpy(type_dest, PQgetvalue(pg_res, 0, 1));

            printf("type = %s\n",type_dest);
            // Vérification si le destinataire est compatible
            if ((strcmp(type_dest, TYPE_MEMBRE) == 0 && strcmp(utilisateur->type, TYPE_PRO) == 0) ||
                (strcmp(type_dest, TYPE_PRO) == 0 && strcmp(utilisateur->type, TYPE_MEMBRE) == 0)) {

                // Récupération de la date et heure actuelle
                time_t t = time(NULL);
                struct tm tm = *localtime(&t);
                char date_buff[BUFFER_SIZE / 2];
                snprintf(date_buff, sizeof(date_buff), "%d-%02d-%02d %02d:%02d:%02d", 
                         tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, 
                         tm.tm_hour, tm.tm_min, tm.tm_sec);



                const char *ajout_message =
                    "INSERT INTO pact.vueMessages (idExpediteur, contenuMessage, dateMessage, typeExpediteur, idReceveur)"
                    "VALUES ($1, $2, $3, $4, $5);";

                char inverted_type_dest[BUFFER_SIZE];

                if (strcmp(type_dest, "membre") == 0) {
                    strcpy(inverted_type_dest, "pro");
                } else if (strcmp(type_dest, "pro") == 0) {
                    strcpy(inverted_type_dest, "membre");
                } else {
                    strcpy(inverted_type_dest, type_dest); // Garde la même valeur si ce n'est ni membre ni pro
                }
                
                
                // Saisit du message dans la bdd
                const char *param_values_addMSG[5] = {
                    utilisateur->identiteUser,
                    requete.elements[3],
                    date_buff,
                    inverted_type_dest,
                    idu_dest
                };

                // char taille_msg[10];
                // snprintf(taille_msg, sizeof(taille_msg), "%lu", strlen(requete.elements[3]));
                // param_values_addMSG[3] = taille_msg;

                PGresult *pg_res_insert = PQexecParams(conn, ajout_message, 5, NULL, param_values_addMSG, NULL, NULL, 0);

                if (PQresultStatus(pg_res_insert) == PGRES_COMMAND_OK) {
                    json_object_object_add(json_obj, "statut", json_object_new_string(REP_200));
                } else {
                    fprintf(stderr, "Erreur lors de l'ajout du message: %s\n", PQerrorMessage(conn));
                    json_object_object_add(json_obj, "statut", json_object_new_string(REP_500));
                }

                PQclear(pg_res_insert);
                send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "success");

            } else {
                json_object_object_add(json_obj, "statut", json_object_new_string(REP_403_UNAUTHORIZED_USE));
                send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
            }
        } else {
            json_object_object_add(json_obj, "statut", json_object_new_string(REP_401_RECIPIENT));
            send_json_request(conn, *utilisateur, json_object_to_json_string(json_obj), "error");
        }
        PQclear(pg_res);
    }
}

void afficher_commande_aide(PGconn *conn, tExplodeRes requete, tClient utilisateur) {

    struct json_object *json_obj = json_object_new_object();
    struct json_object *commandes_array = json_object_new_array();

    // Fonction pour créer une commande
    struct json_object *create_command(
        const char *nom, const char *description, struct json_object *arguments, struct json_object *reponses) {
        struct json_object *cmd = json_object_new_object();
        json_object_object_add(cmd, "nom", json_object_new_string(nom));
        json_object_object_add(cmd, "description", json_object_new_string(description));
        json_object_object_add(cmd, "arguments", arguments);
        json_object_object_add(cmd, "réponses", reponses);
        return cmd;
    }

    // Fonction pour créer un argument
    struct json_object *create_argument(const char *argument, const char *description) {
        struct json_object *arg = json_object_new_object();
        json_object_object_add(arg, "argument", json_object_new_string(argument));
        json_object_object_add(arg, "description", json_object_new_string(description));
        return arg;
    }

    // Fonction pour créer une réponse
    struct json_object *create_response(const char *nom, const char *description) {
        struct json_object *resp = json_object_new_object();
        json_object_object_add(resp, "nom", json_object_new_string(nom));
        json_object_object_add(resp, "description", json_object_new_string(description));
        return resp;
    }

    // Commande LOGIN:
    struct json_object *login_args = json_object_new_array();
    json_object_array_add(login_args, create_argument("clé api", "Clé API de l'utilisateur voulant se connecter"));

    struct json_object *login_responses = json_object_new_array();
    json_object_array_add(login_responses, create_response("200/OK", "Accès autorisé"));
    json_object_array_add(login_responses, create_response("400/TOO MANY ARGS", "Trop de paramètres fournis"));
    json_object_array_add(login_responses, create_response("400/MISSING ARGS", "Pas assez de paramètres fournis"));
    json_object_array_add(login_responses, create_response("403/CLIENT BANNED", "Accès refusé, client banni"));
    json_object_array_add(login_responses, create_response("403/CLIENT BLOCKED", "Accès refusé, client bloqué"));
    json_object_array_add(login_responses, create_response("429/QUOTA EXCEEDED", "Quota dépassé pour la clé API"));

    json_object_array_add(commandes_array, create_command("LOGIN:", "Connexion au service", login_args, login_responses));

    // Commande BYE BYE:
    struct json_object *bye_args = json_object_new_array();
    json_object_array_add(bye_args, create_argument("tokken", "Identifiant de l'utilisateur, reçu lors de la connexion"));

    struct json_object *bye_responses = json_object_new_array();
    json_object_array_add(bye_responses, create_response("200/OK", "Déconnexion réussie"));
    json_object_array_add(bye_responses, create_response("400/TOO MANY ARGS", "Trop de paramètres fournis"));
    json_object_array_add(bye_responses, create_response("400/MISSING ARGS", "Pas assez de paramètres fournis"));
    json_object_array_add(bye_responses, create_response("403/CLIENT BANNED", "Accès refusé, client banni"));
    json_object_array_add(bye_responses, create_response("403/CLIENT BLOCKED", "Accès refusé, client bloqué"));

    json_object_array_add(commandes_array, create_command("BYE BYE:", "Déconnexion du service", bye_args, bye_responses));

    // Commande AIDE:
    struct json_object *aide_args = json_object_new_array();
    json_object_array_add(aide_args, create_argument("fonctionnalité", "Nom d'une fonctionnalité spécifique"));

    struct json_object *aide_responses = json_object_new_array();
    json_object_array_add(aide_responses, create_response("En cours de développement", "La fonctionnalité n'est pas encore disponible"));

    json_object_array_add(commandes_array, create_command("AIDE:", "Affichage des informations sur une fonctionnalité", aide_args, aide_responses));

    // Commande MSG:
    struct json_object *msg_args = json_object_new_array();
    json_object_array_add(msg_args, create_argument("tokken", "Identifiant de l'utilisateur après connexion"));
    json_object_array_add(msg_args, create_argument("clé api destinataire", "Identifiant du destinataire"));
    json_object_array_add(msg_args, create_argument("message", "Contenu du message envoyé"));

    struct json_object *msg_responses = json_object_new_array();
    json_object_array_add(msg_responses, create_response("200/OK", "Message envoyé avec succès"));
    json_object_array_add(msg_responses, create_response("400/TOO MANY ARGS", "Trop de paramètres fournis"));
    json_object_array_add(msg_responses, create_response("400/MISSING ARGS", "Pas assez de paramètres fournis"));
    json_object_array_add(msg_responses, create_response("401/UNAUTH/UNKNOWN RECIPIENT", "Destinataire inconnu"));
    json_object_array_add(msg_responses, create_response("403/CLIENT BANNED", "Accès refusé, client banni"));
    json_object_array_add(msg_responses, create_response("403/CLIENT BLOCKED", "Accès refusé, client bloqué"));
    json_object_array_add(msg_responses, create_response("403/UNAUTHORIZED USE", "Utilisateur non autorisé à envoyer ce message"));
    json_object_array_add(msg_responses, create_response("416/MISFMT", "Message mal formaté ou trop long"));
    json_object_array_add(msg_responses, create_response("429/QUOTA EXCEEDED", "Quota dépassé pour la clé API"));
    json_object_array_add(msg_responses, create_response("500/INTERNAL SERVER ERROR", "Erreur interne du serveur"));

    json_object_array_add(commandes_array, create_command("MSG:", "Envoi d'un message à un autre utilisateur", msg_args, msg_responses));

    // Commande HISTORIQUE:
    struct json_object *historique_args = json_object_new_array();
    json_object_array_add(historique_args, create_argument("tokken", "Identifiant de l'utilisateur après connexion"));

    struct json_object *historique_responses = json_object_new_array(); // Pas de réponse spécifiée dans la doc
    json_object_array_add(commandes_array, create_command("HISTORIQUE:", "Consultation de l'historique des messages", historique_args, historique_responses));

    // Commande STOP:
    struct json_object *stop_args = json_object_new_array(); // Aucun argument
    struct json_object *stop_responses = json_object_new_array();
    json_object_array_add(stop_responses, create_response("En cours de développement", "Sécurisation en cours"));

    json_object_array_add(commandes_array, create_command("STOP:", "Arrêt du serveur (administrateur uniquement)", stop_args, stop_responses));

    // Ajouter la liste des commandes à l'objet JSON principal
    json_object_object_add(json_obj, "commandes", commandes_array);

    const char *json_body = json_object_to_json_string(json_obj);

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
    }
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
        snprintf(buffer, sizeof(buffer), "%d - %s - [INFO] - %s\n", log_id, date_buff, info);
    } else if (strcmp(type, "error") == 0) {
        snprintf(buffer, sizeof(buffer), "%d - %s - [ERROR] - %s\n", log_id, date_buff, info);
    } else if (strcmp(type, "debug") == 0) {
        snprintf(buffer, sizeof(buffer), "%d - %s - [DEBUG] - %s\n", log_id, date_buff, info);
    } else {
        snprintf(buffer, sizeof(buffer), "%d - %s - [UNKNOWN TYPE] - %s\n", log_id, date_buff, info);
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
        return res;
    }

    tExplodeRes tmp = explode(buffer, "|");
    concat_struct(&res, &tmp);

    if (res.nbElement > 1) {
        // Requête pour vérifier le type d'utilisateur
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

        // Paramètres pour la requête
        const char *param_values[1];
        param_values[0] = res.elements[1];

        // Exécuter la requête et récupérer les résultats
        PGresult *pg_res = PQexecParams(conn, query, 1, NULL, param_values, NULL, NULL, 0);
        
        if (PQresultStatus(pg_res) != PGRES_TUPLES_OK) {
            fprintf(stderr, "Erreur d'exécution de la requête : %s\n", PQerrorMessage(conn));
            PQclear(pg_res);
            return res;
        }

        int nrows = PQntuples(pg_res);
        if (nrows > 0) {
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