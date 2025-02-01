/** @file
 * @brief Outils de traitement
 * @author Matteo-K, Gabriel-F, Kylian-H, Ewen-J
 */

#include <stdbool.h>

#include "const.h"

#ifndef OUTILS_H
#define OUTILS_H

/// @brief arrête le service côté serveur par un signal
/// @param sig in: siganl reçu
/// @param info in: information sur le signal
/// @param context in: argument nécessaire pour les signaux
void killChld(int sig, siginfo_t *info, void *context);

/// @brief Envoie les requêtes json du côté client
void send_json_request(PGconn *conn, tClient utilisateur, char json_body[], char type[]);

/// @brief vérifie si une chaine de caractère est une commande
bool est_commande(char buffer[]);

// inspiré du trim de d'autre language
/// @brief Retire les espaces avant et après la chaine de character
/// @param str in: chaine de character
/// @return chaine de character traité
char *trim(char *str);

/// @brief Concaténation de 2 structure tExplodeRes
/// @param struct1 in/out: Structure avec le résultat de la concaténation
/// @param struct2 in/out: Structure pour la première structure
void concat_struct(tExplodeRes *struct1, tExplodeRes *struct2);

// inspiré du explode de d'autre language
/// @brief Sépare une chaine par un séparateur
/// @param buffer in: chaine de character
/// @param separateur in: séparateur de la chaine
/// @return structure de la liste partie de chaine séparé
tExplodeRes explode(char buffer[], const char *separateur);

/// @brief libère l'allocation en mémoire de la structure
/// @param result in/out: structure du résultat de l'explode
void freeExplodeResult(tExplodeRes *result);

/// @brief Affiche les informations du client
/// @param utilisateur client du service
void afficherClient(tClient utilisateur);

/// @brief Affiche les informations de la struture
/// @param structure structure à afficher
void afficherTExplodeRes(tExplodeRes structure);

#endif // OUTILS_H