/** @file
 * @brief BDD postgreSQL
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include "const.h"

#ifndef BDD_H
#define BDD_H

/// @brief Initialise la bdd postgreSQL
PGconn *init_bdd();

/// @brief Cherche en BDD l'existance de la clé API 
int trouveAPI(PGconn *conn, const char *requete);

/// @brief Ajoute / modifie en BDD le tokken de l'utilisateur à sa connexion
void updateBDD(PGconn *conn, const char *requete);

#endif // BDD_H