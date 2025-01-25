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
/// @param buffer in: buffer de la commande reçu
/// @param sockfd in: descripteur du client
/// @return condition d'arrêt du serveur
int gestion_commande(char buffer[], int sockfd);

char *trim(char *str);

#endif // FONCTION_SERVEUR_H