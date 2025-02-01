/** @file
 * @brief Fonction & commande du serveur
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdbool.h>

#include "outils.h"
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

/// @brief Génère le tokken de l'utiliateur, envoyé par connexion()
/// @param key in/out: chaine dans laquelle sera envoée le tokken
void genere_tokken(char *key);

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

tExplodeRes init_argument(PGconn *conn, tClient *utilisateur, char buffer[]);

#endif // FONCTION_SERVEUR_H