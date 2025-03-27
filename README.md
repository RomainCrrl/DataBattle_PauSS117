# DataBattle - PauSS117

## Installation

Pour lancer correctement notre application web, il faut tout d'abord procéder à quelques installations (si vous n'avez pas déja tout installé). 
Dans un premier temps il vous faudra python :

```bash
sudo apt update
sudo apt install python3
sudo apt install python3-pip
```
Ensuite, il vous faudra installer venv afin par la suite de créer un environnement virtuel.

```bash
sudo apt-get install python3-venv
```
venv installer, vous allez maintenant pouvoir procéder à la création de cet environnement virtuel. Pour cela, naviguez jusqu'à votre environnement de travail et tapez la commande suivante :


```bash
python3 -m venv haystack_env
```

L'environnement virtuel créé, lancez-le en tapant :

```bash
source haystack_env/bin/activate
```

Enfin, il vous suffit d'installer les dernières dépendances :

```bash
pip install Flask pandas fuzzywuzzy python-Levenshtein flask-cors
```

Dernière étape. Aller dans le fichier config.php de notre projet et modifier le contenu des variables
```bash
$username = "";
$password = ""
```
par votre identifiant et mot de passe de votre bdd.

## Utilisation

Votre environnement virtuel activé, il vous suffit alors de taper la commande qui suit :

```bash
python3 api.py
```
Dernière étape avant de pouvoir tester notre application web. Ouvrez un nouveau terminal sans fermer le premier ni arrêter la commande précédente. Dans ce nouveau terminal, tapez :

```bash
php -S localhost:8080
```

Toutes ces étapes réalisées, il vous suffit maintenant d'ouvrir votre navigateur préféré et de taper dans la barre de recherche http://localhost:8080


## Contribution

[Volodia GROMYKHOV](https://www.linkedin.com/in/volodia-gromykhov-3595092a2/),
[Romain CORRAL](),[Marc-Antoine ORECCHIONI](https://www.linkedin.com/in/marc-antoine-orecchioni/),
[Matthias RADIN]()

