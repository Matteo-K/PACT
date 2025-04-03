#!/bin/bash

# Connexion SSH et exécution des commandes sur le serveur distant
ssh -i ~/.ssh/id_rsa debian@the-void.ventsdouest.dev << EOF
cd /docker/sae/data
sudo su -c "git pull"
exit
exit
EOF
