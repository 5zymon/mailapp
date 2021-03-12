# Simple Mailer App

## Setup

For your convenience Taskfile (https://taskfile.dev/#/installation) has been provided in the package to automate steps, as an alternative use `make init` to prepare everything. By default 5 users will be generated with 5 contacts each.

http://localhost (The Form)

http://localhost/admin (Data Management)

http://localhost:1000 (Mailhog)

To simulate SMTP server error use task `task mailhog:stop` or just `docker-compose stop mailhog`.

App by default works in `DEV` mode.