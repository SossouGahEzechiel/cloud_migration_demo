# Installation et Lancement de l'Application

Ce projet utilise Docker et Docker Compose pour orchestrer l'environnement de développement (Laravel, PostgreSQL, Redis).

## Prérequis

- [Docker](https://www.docker.com/get-started) installé et lancé sur votre machine.
- [Docker Compose](https://docs.docker.com/compose/install/) (généralement inclus avec Docker Desktop).

## Installation

1. **Cloner le dépôt** (si ce n'est pas déjà fait) :
```bash
   git clone https://github.com/SossouGahEzechiel/cloud_migration_demo.git
   cd cloud_migration_demo
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

4. **Vérifier que les conteneurs sont en cours d'exécution** :
```bash
   docker compose ps
```
   Vous devriez voir 3 conteneurs avec le statut "Up" : `app`, `db`, et `redis`.

   **Si le conteneur `app` ne tourne pas**, vérifiez les logs :
```bash
   docker compose logs app
```
   Puis redémarrez les conteneurs :
```bash
   docker compose down
   docker compose up -d
```

5. **Initialiser l'application** :
   Une fois les conteneurs lancés, exécutez les commandes suivantes pour installer les dépendances et configurer Laravel :
```bash
   # Installer les dépendances PHP
   docker compose exec app composer install
   
   # Générer la clé d'application
   docker compose exec app php artisan key:generate
   
   # Exécuter les migrations de base de données
   docker compose exec app php artisan migrate
```

   **Si ces commandes échouent avec "service 'app' is not running"**, cela signifie que le conteneur `app` n'est pas démarré. Dans ce cas :
```bash
   # Vérifier l'état des conteneurs
   docker compose ps
   
   # Voir les logs pour identifier le problème
   docker compose logs app
   
   # Redémarrer les conteneurs
   docker compose restart app
   
   # Ou reconstruire complètement
   docker compose down
   docker compose up -d --build
```

## Utilisation

- **Application** : Accessible via http://localhost:8000.
- **Base de données** : Accessible sur le port `5432` (Hôte: `localhost`, User/Pass: voir `.env`).

## Commandes Utiles
```bash
# Voir l'état de tous les conteneurs
docker compose ps

# Voir les logs en temps réel
docker compose logs -f

# Voir les logs d'un service spécifique
docker compose logs -f app

# Redémarrer un service spécifique
docker compose restart app

# Accéder au shell du conteneur app
docker compose exec app bash

# Exécuter des commandes Artisan
docker compose exec app php artisan [commande]

# Exécuter des commandes Composer
docker compose exec app composer [commande]
```

## Arrêter l'application

Pour arrêter les conteneurs :
```bash
docker compose down
```

Pour arrêter les conteneurs et supprimer les volumes (⚠️ supprime les données de la base) :
```bash
docker compose down -v
```

## Dépannage

### Le port 5432 est déjà utilisé

Si vous avez PostgreSQL installé localement, arrêtez-le :

**Windows** :
```powershell
Stop-Service postgresql-x64-*
```

**Linux/Mac** :
```bash
sudo systemctl stop postgresql
```

Ou modifiez le port dans `docker-compose.yml` :
```yaml
db:
  ports:
    - "5433:5432"  # Utiliser le port 5433 au lieu de 5432
```

### Le conteneur `app` ne démarre pas

1. Vérifiez les logs :
```bash
   docker compose logs app
```

2. Vérifiez que le fichier `.env` existe et est correctement configuré.

3. Reconstruisez l'image :
```bash
   docker compose build app --no-cache
   docker compose up -d
```

### Erreur "vendor/autoload.php not found"

Installez les dépendances :
```bash
docker compose exec app composer install
```

Si le conteneur ne tourne pas, le Dockerfile devrait installer automatiquement les dépendances. Vérifiez qu'il contient bien `composer install` dans la section `CMD`.
