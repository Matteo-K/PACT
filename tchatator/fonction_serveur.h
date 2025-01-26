/** @file
 * @brief Fonction & commande du serveur
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#ifndef FONCTION_SERVEUR_H
#define FONCTION_SERVEUR_H

/// @brief Initialise le socket
/// @return descripteur du socket
int init_socket();

/// @brief Gestion des commandes du serveur
/// @param conn in/out: connexion avec la bdd
/// @param tokken_connexion in/out: tokken de connexion de l'utilisateur
/// @param buffer in: buffer de la commande reçu
/// @param sockfd in: descripteur du client
/// @return condition d'arrêt du serveur
int gestion_commande(PGconn *conn, char *tokken_connexion, char buffer[], int sockfd, struct sockaddr_in client_addr);

/// @brief Affichage des commandes d'aide avec HELP côté client
/// @param sockfd in: descripteur du client
void afficher_commande_aide(int sockfd);

/// @brief Affichage de l'aide avec --help/-h
void afficher_aide();

/// @brief Affichage des logs avec --verbose/-b
void afficher_logs();

/// @brief Ajoute dans les logs l'action du client
/// @param sockfd in: descripteur du client
/// @param commande in: commande du client
/// @param type in: type de message. ex: info, error, debug
void ajouter_logs(PGconn *conn, char *tokken_connexion, struct sockaddr_in client_addr, char *commande, char *type);

void gestion_option(int argc, char *argv[]);

void killChld(int sig, siginfo_t *info, void *context);

char *trim(char *str);

#endif // FONCTION_SERVEUR_H