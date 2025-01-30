/** @file
 * @brief Constante de tchatator
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <arpa/inet.h>

#ifndef CONST_H
#define CONST_H

#define SERVEUR "the-void.ventsdouest.dev"
#define BUFFER_SIZE 512
#define NB_CONNEXION_MAX 50

// type
typedef struct {
  char identiteUser[BUFFER_SIZE / 3];
  char tokken_connexion[BUFFER_SIZE];
  char client_ip[INET_ADDRSTRLEN];
  char type[BUFFER_SIZE];
  int sockfd;
} tClient;

typedef struct {
    char **elements;
    int nbElement;
} tExplodeRes;

// Type utiliser
/// @brief Character String: type membre d'un client
#define TYPE_MEMBRE "membre"

/// @brief Character String: type pro d'un client
#define TYPE_PRO "pro"

/// @brief Character String: type admin d'un client
#define TYPE_ADMIN "admin"


// Réponse 
/// @brief Character String: L'action c'est bien déroulé
#define REP_200 "200/OK"

/// @brief Character String: Client non identifié
#define REP_401 "401/UNAUTH"

/// @brief Character String: Message mal formaté
#define REP_416 "416/MISFMT"

/// @brief Character String: Manque d'argument
#define REP_400_MISSING_ARGS "400/MISSING_ARGS"

/// @brief Character String: Trop d'argument
#define REP_400_TOO_MANY_ARGS "400/TOO_MANY_ARGS"

/// @brief Character String: Client banni
#define REP_403_BAN "403/CLIENT_BANNED"

/// @brief Character String: Client bloqué
#define REP_403_BLOCK "403/CLIENT_BLOCKED"

/// @brief Character String: Utilisation non autorisée
#define REP_403_UNAUTHORIZED_USE "403/UNAUTHORIZED_USE"

/// @brief Character String: Quota dépassé pour la clé API
#define REP_429_QUOTA_EXCEEDED "429/QUOTA_EXCEEDED"


// paramètre
/// @brief Integer: port de connexion au serveur
#define PORT 8081

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

/// @brief Character String: Arrête le serveur
#define COMMANDE_HISTORIQUE "HISTORIQUE"


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