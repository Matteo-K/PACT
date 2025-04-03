#!/bin/bash

clear

# Vérification si json-c est installé
echo "Vérification de la bibliothèque json-c..."
if ! pkg-config --exists json-c; then
    echo "Erreur : json-c n'est pas installé. Veuillez l'installer avec :"
    echo "  - Debian/Ubuntu : sudo apt install libjson-c-dev"
    echo "  - macOS (Homebrew) : brew install json-c"
    echo "  - Windows (vcpkg) : vcpkg install json-c"
    exit 1
else
    echo "json-c est installé."
fi

# Compilation de tchatator
echo "Compilation du fichier tchatator"
if gcc -o tchatator *.c -lpq $(pkg-config --cflags --libs json-c) -Wall; then
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
