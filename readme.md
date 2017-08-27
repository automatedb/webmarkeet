## Prérequis

Avoir docker et docker compose d'installer sur votre environnement.

## Installation et mise à jour

Installation de l'application :

```bash
docker run --rm --interactive --tty \
       --volume $PWD:/app \
       composer install
```

Ajouter un package à l'application :

```bash
docker run --rm --interactive --tty \
       --volume $PWD:/app \
       composer require PACKAGE_NAME
```

Mise à jour de tous les packages de l'application :

```bash
docker run --rm --interactive --tty \
       --volume $PWD:/app \
       composer update
```

## Démarrage de l'application

Démarrage en tâche de fond :

```bash
docker-compose up -D
```

## Ajouter des données de tests

### Création d'articles

Pour créer des articles, tutoriels et formations fictives, il faut lancer la commande suivante : 

`php artisan migrate:refresh --seed`

### Ajouter un administrateur

Pour ajouter un administrateur, il faut lancer la commande suivante : 

`php artisan make:user --firstname=john --lastname=doe --email=john.doe@domain.tld --password=P@ssword --role=customer`

### Mise à jour des images

Pour associer des images aux articles, il faut lancer la commande : 

`php artisan image:resize`

### Générer un sitemap

Pour générer manuellement un sitemap, il faut lancer la commande : 

`php artisan make:sitemap`

## Contributeurs

* Nicolas MORICET