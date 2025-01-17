#include <stdio.h>
#include <stdlib.h>
#include <postgresql/libpq-fe.h>

const char *pgHost = "the-void.ventsdouest.dev";
const char *pgPort = "5432";
const char *pgOptions = NULL;
const char *pgTTY = NULL;
const char *dbName = "sae";
const char *login = "sae";
const char *pwd = "digital-costaRd-sc0uts";

int main() {
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
            printf("Connexion en cours...\n");
            break;
        case CONNECTION_MADE:
            printf("Connexion établie avec succès !\n");
            break;
        case CONNECTION_OK:
            printf("Connexion OK.\n");
            break;
        default:
            printf("Statut de connexion inconnu.\n");
    }

    // Fermer la connexion à la fin
    PQfinish(conn);

    return 0;
}
