#!/bin/bash

clear

# Compilation de tchatator
echo "Compilation du fichier tchatator"
if gcc -o tchatator tchatator.c const.c fonction_serveur.c bdd.c -lpq -Wall; then
    echo "Compilation de tchatator terminée avec succès."
else
    echo "Erreur lors de la compilation de tchatator. Arrêt du script."
    exit 1
fi

# Exécution de tchatator
echo "Exécution du programme tchatator"
./tchatator "$@"

# Nettoyage des fichiers exécutables
echo "Nettoyage : suppression des fichiers exécutables"
rm -f tchatator

echo "Fin du script."
