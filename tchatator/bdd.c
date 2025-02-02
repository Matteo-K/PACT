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

void updateBDD(PGconn *conn, const char *requete) {
    PQexec(conn, requete);
}

int execute_requete(PGconn *conn, const char *nom_query, const char *query, int nb_param, const char *valeur_param[]) {

    // Vérification si la requête est déjà préparée avant de la préparer à nouveau
    PGresult *res_prepar = PQprepare(conn, nom_query, query, nb_param, NULL);
    if (PQresultStatus(res_prepar) != PGRES_COMMAND_OK) {
        fprintf(stderr, "Erreur lors de la préparation de la requête : %s\n", PQerrorMessage(conn));
        PQclear(res_prepar);
        return -1;
    }
    PQclear(res_prepar);

    // Exécution de la requête préparée
    PGresult *pg_res = PQexecPrepared(conn, nom_query, nb_param, valeur_param, NULL, NULL, 0);
    
    if (PQresultStatus(pg_res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
        PQclear(pg_res);
        return -1;
    }
    
    int nrows = PQntuples(pg_res);
    PQclear(pg_res);

    return nrows;
}
