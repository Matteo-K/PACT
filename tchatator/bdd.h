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

/// @brief Vérifie si l'utilisateur existe (avec sa clé API) et l'identifie
int connexion(PGconn *conn, tClient *utilisateur);

#endif // BDD_H