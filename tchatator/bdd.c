/** @file
 * @brief BDD postgreSQL
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <postgresql/libpq-fe.h>

#include "bdd.h"
#include "const.h"

PGconn *init_bdd() {
    PGconn *conn;  // Pointeur vers la structure de connexion PostgreSQL

    // Établir la connexion à la base de données
    conn = PQsetdbLogin(pgHost, pgPort, pgOptions, pgTTY, dbName, login, pwd);

    // Vérifier si la connexion a réussi
    if (PQstatus(conn) != CONNECTION_OK) {
        fprintf(stderr, "Échec de la connexion : %s\n", PQerrorMessage(conn));
        PQfinish(conn);  // Fermer la connexion
        exit(1);
    }

    // Afficher le statut de la connexion
    switch (PQstatus(conn)) {
        case CONNECTION_STARTED:
            printf("Connexion BDD en cours...\n");
            break;
        case CONNECTION_MADE:
            printf("Connexion BDD établie avec succès !\n");
            break;
        case CONNECTION_OK:
            printf("Connexion BDD OK.\n");
            break;
        default:
            printf("Statut de connexion BDD inconnu.\n");
    }

    return conn;
}


int trouveAPI(PGconn *conn, const char *requete) {
    int nb;
    char *valeur_str;
    int valeur;

    PGresult *res = PQexec(conn, requete);

    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
        PQclear(res);
        return -1; // Retourne -1 en cas d'erreur
    }

    nb = PQntuples(res); // Nombre de lignes dans le résultat
    if (nb == 0) {
        fprintf(stderr, "Aucun résultat trouvé pour la clé API.\n");
        PQclear(res);
        return -1; // Retourne -1 aussi si aucune donnée
    }

    valeur_str = PQgetvalue(res, 0, 0);
    valeur = atoi(valeur_str);

    PQclear(res); // Libérer la mémoire
    return valeur; // Retourne la valeur entière
}


int connexion(PGconn *conn, tClient *utilisateur){

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
            strcpy(utilisateur->type, "membre");
            printf("Vous êtes un membre");
        }
        else if(trouveAPI(conn, requetePro) > 0){
            strcpy(utilisateur->type, "pro");
            printf("Vous êtes un professionnel");
        }
        else if (trouveAPI(conn, requeteAdmin) > 0){
            strcpy(utilisateur->type, "admin");
            printf("Vous êtes un administrateur");
        }
        else{
            strcpy(utilisateur->type, "inconnu");
            printf("Vous êtes un individu sans catégorie");
        }

        return idu;
    }
    else{
        printf("Clé API inexistante, veuillez la consulter sur le site PACT, dans la section 'Mon compte'");
    }
    return -1;
}