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

## Contributeurs

* Nicolas MORICET