# ecoride

Plateforme de covoiturage écologique – Projet DWWM  
Développée avec Symfony (PHP), Twig, SCSS, Bootstrap, JavaScript.

![Symfony](https://img.shields.io/badge/Symfony-6.x-black?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-^8.1-blue?logo=php)
![Composer](https://img.shields.io/badge/Composer-dependencies-success?logo=composer)
![Node.js](https://img.shields.io/badge/Node.js->=18-green?logo=node.js)
![Build](https://github.com/Mirtylart/ecoride/actions/workflows/ci.yml/badge.svg)

---

## Table des matières

- [À propos](#à-propos)  
- [Prérequis](#prérequis)  
- [Installation](#installation)  
- [Configuration](#configuration)  
- [Lancement en local](#lancement-en-local)  
- [Tests](#tests)  
- [Contribuer](#contribuer)  
- [Licence](#licence)

---

## À propos

ecoride est une application web de covoiturage écologique permettant aux utilisateurs de :  
- Créer et rechercher des trajets partagés  
- S’inscrire et se connecter  
- Gérer leur profil  
- Réduire l’empreinte carbone en favorisant le covoiturage  

---

## Prérequis

Avant l’installation, assurez-vous d’avoir :  

- **PHP ≥ 8.1**  
- **Composer**  
- **Node.js & npm** ou **Yarn**  
- **Git**

---

##  Installation

1. Cloner le projet

   ```bash
   git clone https://github.com/Mirtylart/ecoride.git
   cd ecoride
   ```

2. Installer les dépendances backend

   ```bash
   composer install
   composer require mongodb/mongodb
   composer require doctrine/doctrine-mongodb-odm-bundle
   ```

3. Installer les dépendances frontend (Bootstrap + Webpack Encore)

   ```bash
   npm install
   # ou
   yarn install
   ```
4. Compiler les assets

   ```bash
   npm run dev  
   # ou
   npm run build 
   ```


5. Configurer l’environnement

   Copier le fichier `.env.dev` vers `.env` puis ajuster les variables (BDD, clés API, etc.) :

   ```bash
   cp .env.dev .env
   ```

6. Base de données

   ```bash
   php bin/console doctrine:database:create
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:mongodb:schema:create
   ```


##  Configuration

- Variables d’environnement dans `.env` :  
  - `DATABASE_URL` → connexion à la base  
  - autres clés si nécessaire (API, SMTP, etc.)  
- Docker est configuré via `compose.yaml` et `compose.override.yaml`.  

---

##  Lancement en local

- Lancer le serveur Symfony :

  ```bash
  symfony server:start
  # ou
  php -S localhost:8000 -t public
  ```

- Lancer le watcher front :

  ```bash
  npm run dev
  ```

Application accessible sur [http://localhost:8000](http://localhost:8000)

---

## Fixtures/Test

Pour exécuter les fixtures :

```bash
php bin/console doctrine:fixtures:load
php bin/phpunit
```

---

##  Contribuer

1. Fork le projet  
2. Crée une branche (`feature/ma-fonctionnalite`)  
3. Commit les changements  
4. Push la branche  
5. Ouvre une Pull Request  

---

##  Licence

Projet réalisé dans le cadre de la formation DWWM.  
Licence : libre.

---
