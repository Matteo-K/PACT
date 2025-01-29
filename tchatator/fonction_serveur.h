/** @file
 * @brief Fonction & commande du serveur
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include "const.h"

#ifndef FONCTION_SERVEUR_H
#define FONCTION_SERVEUR_H

/// @brief Initialise le socket
/// @return descripteur du socket
int init_socket();

/// @brief Gestion des commandes du serveur
/// @param conn in/out: connexion avec la bdd
/// @param utilisateur in/out: Information de l'utilisateur
/// @param buffer in: buffer de la commande reçu
void gestion_commande(PGconn *conn, char buffer[], tClient *utilisateur);

/// @brief Vérifie si l'utilisateur existe (avec sa clé API) et l'identifie
/// @return renvoie le résultat de la commande / erreur
int connexion(PGconn *conn, tClient *utilisateur, char buffer[]);

void saisit_message(PGconn *conn, tClient utilisateur, char buffer[]);

/// @brief Affichage des commandes d'aide avec HELP côté client
/// @param utilisateur in: information de l'utilisateur
void afficher_commande_aide(tClient utilisateur);

/// @brief Affichage de l'aide avec --help/-h
void afficher_aide();

/// @brief Affichage des logs avec --verbose/-b
void afficher_logs();

/// @brief Ajoute dans les logs l'action du client
/// @param utilisateur in: information de l'utilisateur
/// @param commande in: commande du client
/// @param type in: type de message. ex: info, error, debug
void ajouter_logs(PGconn *conn, tClient utilisateur, char *commande, char *type);

void gestion_option(int argc, char *argv[]);

void killChld(int sig, siginfo_t *info, void *context);

void send_json_request(int sock, const char *json_body);

char *trim(char *str);

tExplodeRes explode(char *buffer, char *separateur);

#endif // FONCTION_SERVEUR_H