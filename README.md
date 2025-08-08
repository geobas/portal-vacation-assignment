## PHP Developer Assignment

---

### Set up
Clone repository
```
git clone https://github.com/geobas/portal-vacation-assignment.git epignosis
```
To build and start the containers from inside root directory
```
./start-containers.sh 
```
or from inside 'docker' directory
```
docker-compose up -d
```
Then from inside 'docker' directory
```
docker compose exec -it php bash
```
Download packages
```
composer install
```
Go to http://localhost:8081 login with Username: user & Password: user and import epignosis.sql, that's located to 'code' directory, into 'epignosis' database

Go to http://localhost:8080 login with Username: manager & Password: manager

### Execute tests
From inside container
```
composer test
```
