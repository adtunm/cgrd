To start project execute command
```
docker compose -f docker-compose.yml up -d
```
`.env` file is required to run this app.
I published it because there isn't any secret data.

With docker build also database with initial values are executed.

After successful docker build under url: `localhost` application is available.
initial login data are:

Login:`admin`<br>
Password: `test`
