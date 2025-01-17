#!/bin/bash

# Compilation de bdd.c
echo "Compilation du fichier bdd.c"
if gcc -o bdd bdd.c -lpq -Wall; then
    echo "Compilation de bdd.c terminée avec succès."
else
    echo "Erreur lors de la compilation de bdd.c. Arrêt du script."
    exit 1
fi

# Compilation de tchatator.c
echo "Compilation du fichier tchatator.c"
if gcc -o tchatator tchatator.c -lpq -Wall; then
    echo "Compilation de tchatator.c terminée avec succès."
else
    echo "Erreur lors de la compilation de tchatator.c. Arrêt du script."
    exit 1
fi

# Exécution de tchatator
echo "Exécution du programme tchatator"
./tchatator

# Nettoyage des fichiers exécutables
echo "Nettoyage : suppression des fichiers exécutables"
rm -f bdd tchatator

echo "Fin du script."
