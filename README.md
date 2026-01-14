# Installation et Lancement de l'Application

Ce projet utilise Docker et Docker Compose pour orchestrer l'environnement de développement (Laravel, PostgreSQL, Redis).

## Prérequis

- [Docker](https://www.docker.com/get-started) installé et lancé sur votre machine.
- [Docker Compose](https://docs.docker.com/compose/install/) (généralement inclus avec Docker Desktop).

## Installation

1. **Cloner le dépôt** (si ce n'est pas déjà fait) :
   ```bash
   git clone <votre-repo-url>
   cd cm_demo
   ```

2. **Configurer les variables d'environnement** :
   Copiez le fichier d'exemple `.env.example` vers `.env` :
   ```bash
   cp .env.example .env
   ```

   Ouvrez le fichier `.env` et assurez-vous que la configuration de la base de données correspond aux attentes de Docker (le service de base de données s'appelle `db`) :

   ```ini
   DB_CONNECTION=pgsql
   DB_HOST=db
   DB_PORT=5432
   DB_DATABASE=laravel
   DB_USERNAME=postgres
   DB_PASSWORD=password
   ```
   *(Ajustez les valeurs `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD` selon vos besoins, Docker les utilisera pour initialiser le conteneur PostgreSQL).*

3. **Lancer les conteneurs** :
   Construisez et démarrez les services en arrière-plan :
   ```bash
   docker compose up -d --build
   ```

4. **Initialiser l'application** :
   Une fois les conteneurs lancés, exécutez les commandes suivantes pour installer les dépendances et configurer Laravel :

   ```bash
   # Installer les dépendances PHP
   docker compose exec app composer install

   # Générer la clé d'application
   docker compose exec app php artisan key:generate

   # Exécuter les migrations de base de données
   docker compose exec app php artisan migrate
   ```

## Utilisation

- **Application** : Accessible via http://localhost:8000.
- **Base de données** : Accessible sur le port `5432` (Hôte: `localhost`, User/Pass: voir `.env`).

## Arrêter l'application

Pour arrêter les conteneurs :
```bash
docker compose down
```
