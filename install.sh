#!/bin/bash

#install stuff
what=${PWD##*/}   
extension=.py
#peut être extension vide

pip install netifaces
pip install websockets

## Créer le répertoire de configuration
confDir=~/.config/$what/
echo "Create config directory  '$confDir'"
mkdir -p $confDir


echo "Set executable..."
chmod +x $what$extension
#echo "lien symbolique vers usr bin"
sudo ln -s "$PWD/$what$extension" /usr/bin/$what
echo "done."
