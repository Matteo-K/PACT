/** @file
 * @brief Constante de tchatator
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stddef.h>

#include "const.h"

// paramètre
const int PORT = 8102;
const int DUREE_BAN = -1;      // définitif
const int DUREE_BLOCAGE = 86400;
const int TAILLE_MAX_MSG = 499;
const int NB_MAX_BLOC_MSQ = 20;
const int NB_MAX_MSQ_MIN = 12;
const int NB_MAX_MSQ_HR = 90;
const char *CHEMIN_LOGS = "LOGS.log";

// connexion bdd
const char *pgHost = "the-void.ventsdouest.dev";
const char *pgPort = "5432";
const char *pgOptions = NULL;
const char *pgTTY = NULL;
const char *dbName = "sae";
const char *login = "sae";
const char *pwd = "digital-costaRd-sc0uts";