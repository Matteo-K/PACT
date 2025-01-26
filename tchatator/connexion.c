/** @file
 * @brief Test connexion au service
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <postgresql/libpq-fe.h>

#include "bdd.h"
#include "const.h"

int connexion(PGconn *conn){

    char *cleAPI;
    char *requete;
    char *paramValues;
    int idu;

    printf("Bienvenue sur le service de discussion Tchatator \n Entrez votre clé API : ");
    scanf("%s", cleAPI);

    requete = "SELECT idu FROM pact._utilisateur WHERE apikey = $1;";
    paramValues[1] = cleAPI;

    idu = trouveAPI(conn, requete);

    if(idu != 0){
        printf("Connexion réussie, utilisateur n°%d", idu);
        return idu;
    }
    else{
        printf("Clé API inexistante, veuillez la consulter sur le site PACT, dans la section 'Mon compte'");
        return -1;
    }

}

int main() {
    PGconn *conn = init_bdd();

    connexion(conn);

    PQfinish(conn);
}