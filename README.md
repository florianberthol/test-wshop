Installation
=============
```
docker compose build
docker compose up -d
```

Un dump de la base est disponible à la racine du projet : `dump.sql`
Pour le restaurer, vous pouvez utiliser la commande suivante :
```
docker compose exec -T wshop-mariadb mariadb -D test_db -u root -proot < dump.sql
```

Usage
======
Pour accéder à l'application, ouvrez votre navigateur et allez à l'adresse suivante pour obtenir la documentation Swagger :
```
http://localhost/swagger.html
```