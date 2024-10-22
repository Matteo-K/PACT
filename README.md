# PACT

## Astuces Git pour jeune développeur

### Clone du dépot

```bash
git clone <URL-dépot>
```

Exemple :

```bash
git clone https://github.com/Matteo-K/PACT.git
```

### Branche

#### Se déplacer dans une branche

```bash
git checkout <nom-de-la-branche>
```

#### Vérifier dans quelle branche on se situe

```bash
git branch
```

#### Affiche l'ensemble des branches

```bash
git branch -r
```

### Remote

#### Voir le remote configuré

```bash
git remote -v
```

#### Ajouter un remote

```bash
git remote add <nom-du-remote> <URL-du-dépôt>
```

Exemple :

```bash
git remote add PACT https://github.com/Matteo-K/PACT.git
```

### Retirer un remote

```bash
git remote remove <nom-du-remote>
```

### Récupérer les fichiers du dépot

#### Pull - avec fusion des fichiers

```bash
git pull <nom-du-remote> <nom-de-la-branche>
```

#### Fetch - sans fusion des fichiers

```bash
git fetch <nom-du-remote>
```

### Publier des fichiers sur le dépot

#### Ajouter un fichier dans un commit

```bash
git add nomFichier.extension
```

#### Ajouter tout les fichiers dans un commit

```bash
git add .
```

#### Ajouter tout les fichiers modifiés sans les fichiers supprimés

```bash
git add -A
```

#### Faire un commit

```bash
git commit -m "Message de commit décrivant les changements"
```

#### Push - Publier les fichiers sur le github

Pour publier sur github, il faut push un commit soit mettre les fichiers dans le commit. Puis faire le commit. Et enfin faire le push pour qu'il soit accessible à tous.

```bash
git push <nom-du-remote> <nom-de-la-branche>
```

Exemple :

```bash
git push PACT main
```

```bash
git push PACT mattéo
```

#### Vérifier l'historique des commits

```bash
git log
```

## Sprint

### Sprint 0 - Conception

#### Maquette

#### Charte Graphique

#### Dictionnaire de donnée

#### UML
