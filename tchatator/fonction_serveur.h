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

/// @brief Connecte l'utilisateur au service en lui envoyant un tokken
/// @param conn in/out: connexion avec la bdd
/// @param utilisateur in/out: Information de l'utilisateur
/// @param buffer in: buffer de la commande reçu
void connexion(PGconn *conn, tClient *utilisateur, char cleAPI[]);

/// @brief Ajout d'un message par l'utilisateur entre un pro et membre
/// @param conn in/out: connexion avec la bdd
/// @param utilisateur in/out: Information de l'utilisateur
/// @param buffer in: buffer de la commande reçu
void saisit_message(PGconn *conn, tClient *utilisateur, char buffer[]);

/// @brief mets à jour les logs et envoie un message à l'utilisateur
/// @param conn in/out: connexion avec la bdd
/// @param utilisateur in: Information de l'utilisateur
/// @param code_e in: code de l'erreur
void envoie_erreur(PGconn *conn, tClient utilisateur, char code_e[]);

/// @brief Affichage des commandes d'aide avec HELP côté client
/// @param utilisateur in: information de l'utilisateur
void afficher_commande_aide(tClient utilisateur);

/// @brief Affichage de l'aide avec --help/-h
void afficher_aide();

/// @brief Affichage des logs avec --verbose/-b
void afficher_logs();

/// @brief Ajoute dans les logs l'action du client
/// @param utilisateur in: information de l'utilisateur
/// @param message in: message à ajouter dans les logs
/// @param type in: type de message. ex: info, error, debug
void ajouter_logs(PGconn *conn, tClient utilisateur, char *message, char *type);

/// @brief gère les options lors de l'execution du fichier
/// @param argc in: nombre d'argument
/// @param argv in: liste des arguments
void gestion_option(int argc, char *argv[]);

/// @brief arrête le service côté serveur par un signal
/// @param sig in: siganl reçu
/// @param info in: information sur le signal
/// @param context in: argument nécessaire pour les signaux
void killChld(int sig, siginfo_t *info, void *context);


// fonction outils de manipulation
/// @brief Envoie les requêtes json du côté client
/// @param sock in: descripteur du socket
/// @param json_body in: corps du json à envoyer
void send_json_request(int sock, const char *json_body);

// inspité du trim de d'autre language
/// @brief Retire les espaces avant et après la chaine de character
/// @param str in: chaine de character
/// @return chaine de character traité
char *trim(char *str);

// inspité du explode de d'autre language
/// @brief Sépare une chaine par un séparateur
/// @param buffer in: chaine de character
/// @param separateur in: séparateur de la chaine
/// @return structure de la liste partie de chaine séparé
tExplodeRes explode(char buffer[], const char *separateur);

/// @brief libère l'allocation en mémoire de la structure
/// @param result in/out: structure du résultat de l'explode
void freeExplodeResult(tExplodeRes *result);

#endif // FONCTION_SERVEUR_H