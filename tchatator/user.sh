#!/bin/bash
# terminal utilisateur avec telnet

# Vérifie si telnet est installé
if ! command -v telnet &> /dev/null; then
  sudo apt-get update && sudo apt-get install -y telnet
fi

clear

# Fonction pour se connecter au serveur
connect_to_server() {
    echo "Connexion au serveur..."
    telnet the-void.ventsdouest.dev 8081
    local exit_code=$?
    
    if [ $exit_code -ne 0 ]; then
        echo "La connexion au serveur a été perdue."
        return 1
    fi
    return 0
}

# Boucle principale
while true; do
    connect_to_server
    
    if [ $? -ne 0 ]; then
        echo "Tentative de reconnexion dans 5 secondes..."
        sleep 5
    else
        echo "Le serveur a été arrêté. Fermeture du client."
        exit 0
    fi
done