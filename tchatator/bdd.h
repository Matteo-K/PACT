/** @file
 * @brief BDD postgreSQL
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#ifndef BDD_H
#define BDD_H

/// @brief Initialise la bdd postgreSQL
PGconn *init_bdd();

/// @brief Cherche en BDD l'existance de la clé API 
int trouveAPI(PGconn *conn, const char *requete);

/// @brief Execute une requête avec des paramètres flexible
char *execute_requete(PGconn *conn, const char *requete, int nbPram, const char *paramValues[]);

#endif // BDD_H