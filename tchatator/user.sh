#!/bin/bash
# terminal utilisateur avec telnet

# Vérifie si telnet est installé
if ! command -v telnet &> /dev/null; then
  apt-get update && apt-get install -y telnet
fi

clear


# the-void.ventsdouest.dev
telnet the-void.ventsdouest.dev 8102

