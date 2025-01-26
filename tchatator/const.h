/** @file
 * @brief Constante de tchatator
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#ifndef CONST_H
#define CONST_H

#define BUFFER_SIZE 512
#define NB_CONNEXION_MAX 50

// paramètre
/// @brief Integer: port de connexion au serveur
#define PORT 8080

/// @brief Integer: durée de ban
extern const int DUREE_BAN;

/// @brief Integer: durée de blocage
extern const int DUREE_BLOCAGE;

/// @brief Integer: nombre maximum de taille de message
extern const int TAILLE_MAX_MSG;

/// @brief Integer: nombre maximum de bloc de message
extern const int NB_MAX_BLOC_MSQ;

/// @brief Integer: nombre maximum de message envoyé par minute
extern const int NB_MAX_MSQ_MIN;

/// @brief Integer: nombre maximum de message envoyé par heure
extern const int NB_MAX_MSQ_HR;

/// @brief Character String: Chemin fichier logs
extern const char *CHEMIN_LOGS;

// Commande
/// @brief Character String: commande de connexion
#define COMMANDE_CONNEXION "LOGIN:"

/// @brief Character String: commande d'envoie de message
#define COMMANDE_MESSAGE "MSG:"

/// @brief Character String: Déconnexion utilisateur
#define COMMANDE_DECONNECTE "BYE BYE"

/// @brief Character String: Affiche l'aide des commandes
#define COMMANDE_AIDE "AIDE"

/// @brief Character String: Arrête le serveur
#define COMMANDE_STOP "STOP"


// connexion bdd
/// @brief Character String: 
extern const char *pgHost;

/// @brief Character String: 
extern const char *pgPort;

/// @brief Character String: 
extern const char *pgOptions;

/// @brief Character String: 
extern const char *pgTTY;

/// @brief Character String: 
extern const char *dbName;

/// @brief Character String:
extern const char *login;

/// @brief Character String:
extern const char *pwd;

#endif // CONST_H