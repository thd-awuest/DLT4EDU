# DLT4EDU-Service Application

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Pre-Config

Rename ``config/.env_local`` to ``.env``

## Installation

Create docker network (Linux systems only)
```bash
docker network create dlt4edu
```
If you want to start the containers an a non linux system, you have to comment the following lines inside the docker-compose.yml:
```
networks:
default:
external: true
name: dlt4edu
```
so it looks like this:
```
# networks:
# default:
# external: true
# name: dlt4edu
```

Check if creation was succesfull
```bash
docker network ls
```

Run Docker Container local

```bash
docker-compose up
```

-- OR --

Run Docker on Linux host
```bash
docker-compose -p dlt4edu-service up -d
```

Execute into bash

```bash
// CakePHP Container
docker exec -it dlt4edu-service_cakephp_1 /bin/bash
// MYSQL Container
docker exec -it dlt4edu-service_mysql_1 /bin/bash
```

Install PHP dependencies (from inside the container dlt4edu-service_cakephp_1)

```bash
composer install
```

## Enviroment Variables

``.env``-Datei in ``/config/``

## Configuration

Database: `config/app_local.php` Node
`'Datasources'`.
Other environment agnostic settings can be changed in `config/app.php` (z.B. production).


## SAML2 / SSO

``composer require onelogin/php-saml:3.6.1``

## Important Links

https://dlt4edu.th-deg.de/users/my <- listet die Attribute des angemeldeten Benutzers auf

https://dlt4edu.th-deg.de <- Einstiegsseite

https://dlt4edu.th-deg.de/users/login <- Login SSO

https://dlt4edu.th-deg.de/users/logout2 <- Logout

Hinweis: Im nicht angemeldeten Zustand kann derzeit nur die Einstiegsseite und die Action "Login" aufgerufen werden.
Beim Versuch auf ``/my`` zu gehen wird man auf die Einstiegsseite weitergeleitet.

## Project specific information


