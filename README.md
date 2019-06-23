# docker-with-symfony4-and-mysql-example
This is an example setup of two Docker containers running Symfony 4.3:
- `mysql`: Running MySQL 8.0.
- `web`: Running PHP 7.2 on Debian's Apache httpd.

## Prerequisites
- Docker
- Make
- A web browser

## File structure
Familiarise yourself with the file structure. It will help you understand what everything does.

### `service/web`
- `php.ini` is your custom PHP configuration.
- `Dockerfile`:
  - copies the aforementioned `php.ini` file
  - installs dependencies
  - sets the document root and working directory

### `app`
The Symfony app that is synced with `/var/www/html` on the `web` container.

### `.env.example`
Example file for you to copy, when creating your custom `.env` file. The `.env` file will contain environment variables
that are used to build the containers.

### `.gitignore`
Tells `git` to ignore the `.env` file from commits, since it's environment-specific.

### `docker-compose.yml`
Defines the `mysql` and `web` containers.

### `Makefile`
Defines `make` commands to be run on the host machine in the project's root directory.

This includes common Docker commands, but you can add other common developer commands as well.

## Build the containers
1. Copy `.env.example` to create your own `.env` file in the root project directory.
1. Run `make clean` to build the Docker containers, as well as the app itself.

## Connect to the `web` container
1. Run `make web` to connect to the `web` container as the `root` user in a new Bash session.
1. Run `ls -la`, and optionally try some other Bash commands.
1. Run `exit` when you're done.

## Connect to the `mysql` container
1. Run `make mysql` to connect to the `mysql` container as the `root` user in a new MySQL session.
1. Run `show databases;`, and optionally try some other MySQL commands.
1. Run `exit` when you're done.

If you'd prefer to connect via a database management app, the details are:

| Label    | Value                                                  |
| -------- | ------------------------------------------------------ |
| Host     | `0.0.0.0`                                              |
| Username | `root`                                                 |
| Password | The value of `MYSQL_ROOT_PASSWORD` in your `.env` file |
| Port     | The value of `HOST_PORT_MYSQL` in your `.env` file     |

## Test the app
1. Visit `http://localhost:80` in your web browser, where `80` is the `HOST_PORT_HTTP` variable in your `.env`
file. This should display `Homepage`.
1. Visit the path `/test-rewrite` to confirm URL rewriting is working correctly.
1. Visit the path `/sample-asset.txt` to confirm that files within `app/public` are accessible. 

## Container logs
If a container falls over, check `make logs-mysql` or `make logs-web` to work out why.

## Notes:
- If the containers are killed (e.g. you restart your machine), running `make up` will restart them, without losing
any modifications (i.e. if you have manually modified the database or filesystem after `make clean`).
- If you run `make clean` again, this will destroy the containers and create new ones.
