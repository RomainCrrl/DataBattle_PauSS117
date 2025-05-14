# DataBattle - PauSS117

## Installation

Pour lancer correctement notre application web, il faut tout d'abord procéder à quelques installations (si vous n'avez pas déjà tout installé).
Dans un premier temps, il vous faudra Python :

```bash
sudo apt update
sudo apt install python3
sudo apt install python3-pip
```

Ensuite, il vous faudra installer `venv` afin de créer un environnement virtuel :

```bash
sudo apt-get install python3-venv
```

Une fois `venv` installé, vous pouvez créer l’environnement virtuel. Naviguez jusqu'à votre environnement de travail et tapez la commande suivante :

```bash
python3 -m venv haystack_env
```

L’environnement virtuel créé, lancez-le en tapant :

```bash
source haystack_env/bin/activate
```

Installez ensuite les dépendances nécessaires :

```bash
pip install Flask pandas fuzzywuzzy python-Levenshtein flask-cors
pip install Elasticsearch
```

## Initialisation de la base de données MySQL

Ouvrez un terminal et connectez-vous à MySQL :

```bash
mysql -u root -p
```

Entrez votre mot de passe administrateur MySQL, puis exécutez les commandes suivantes :

```sql
-- Assouplir la politique de mot de passe si nécessaire
SET GLOBAL validate_password.policy = LOW;
SET GLOBAL validate_password.length = 4;

-- Création de la base de données et de la table users
CREATE DATABASE dataBattle;
USE dataBattle;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Création de l'utilisateur avec mot de passe simple
CREATE USER 'romain'@'localhost' IDENTIFIED BY 'bddromain';
GRANT ALL PRIVILEGES ON dataBattle.* TO 'romain'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Utilisation

Avec votre environnement virtuel activé, lancez le backend Flask avec :

```bash
python3 api.py
```

Ensuite, ouvrez un **nouveau terminal** sans fermer le premier ni arrêter la commande précédente, et tapez :

```bash
php -S localhost:8080
```

Une fois toutes ces étapes réalisées, ouvrez votre navigateur préféré et rendez-vous à l’adresse suivante :
[http://localhost:8080](http://localhost:8080)

## Contribution

\[Volodia GROMYKHOV]
\[Romain CORRAL]
\[Marc-Antoine ORECCHIONI]
\[Matthias RADIN]
