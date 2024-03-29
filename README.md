<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## HelloCSE - Test technique

-   Laravel v11.x
-   PHP 8.2 ou 8.3
-   MySQL v8.3.x (par défaut)

## Prérequis

-   Docker et Docker compose (Si utilisation de la BDD par défaut)
-   PHP 8.2+
-   Composer

## Installation

Copier le fichier d'environnement par défaut

```bash
cp .env.example .env
```

Si utilisation de **MySQL**, sinon modifier le ficher .env par votre propre configuration

```bash
docker compose up -d
```

Installer les dépendances

```bash
composer install
```

Générer la clé de sécurité

```bash
php artisan key:generate
```

Lancer les migrations et les seeders

```bash
php artisan migrate:fresh --seed
```

Lancer le serveur sur [localhost:8000](https://localhost:8000)

```bash
php artisan serve
```

## Informations

-   **La documentation** de l'API est disponible sous [localhost:8000/api/documentation/v1](http://localhost:8000/api/documentation/v1)

-   **Laravel Telescope** est disponible sous [localhost:8000/telescope](http://localhost:8000/telescope)

-   Implémentation de la logique d'autorisation via les "abilities" des jetons d'accès API de Sanctum pour l'accès à l'API, ainsi que des **Policies** pour la logique métier

-   J'ai mis em place un début d'implémentation de **versioning de l'API** via un système de préfixe. Dans un cas concret, il faudrait probablement utiliser des headers personnalisés

-   Utilisation du workflow **Gitflow**

-   J'ai choisi de ne pas utiliser davantage **Docker** pour ce test technique (Je précise car j'ai hésité à implémenter un environnement complet Traefik+Nginx+PHP+MySQL)

-   Un fichier **Makefile** est disponible à la racine pour améliorer la productivité de l'équipe avec divers commandes, par exemple :
    -   ```bash
          make help
          # Affiche toutes les commandes du fichier
        ```
    -   ```bash
          make before-commit
          # Génère les fichiers helpers
          # Lance le linter (Pint)
          # Lance l'analyse du code statique (PHPStan avec Larastan)
          # Génère les fichiers de documentation
          # Lance les tests
        ```
