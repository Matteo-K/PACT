/** @file
 * @brief Constante de tchatator
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#ifndef CONST_H
#define CONST_H

#define BUFFER_SIZE 256

/// @brief Character String: port de connexion au serveur
#define PORT 8080

// Commande

/// @brief Character String: commande de connexion
#define COMMANDE_CONNEXION "LOGIN:"

/// @brief Character String: commande d'envoie de message
#define COMMANDE_MESSAGE "MSG:"


// connexion bdd
extern const char *pgHost;
extern const char *pgPort;
extern const char *pgOptions;
extern const char *pgTTY;
extern const char *dbName;
extern const char *login;
extern const char *pwd;

#endif // CONST_H