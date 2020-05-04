#Install
```
php composer.phar install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
```

#Prod
```
php composer.phar require symfony/apache-pack

php bin/console cache:clear
```

#Local version Mathieu
```
#/Applications/MAMP/bin/startMysql.sh

symfony server:start
```

#migrer user
```
INSERT INTO `User` (`id`, `username`, `roles`, `password`)
VALUES
	(6, 'mathieu', CONVERT(X'blablabla' using utf8mb4), 'passwordcryptonite');
```

#request to deactivate inactive user
```
SELECT * FROM Player WHERE id NOT IN ( SELECT DISTINCT idPlayer1 AS idPlayer FROM Matchs WHERE date>"2019-04-19" UNION SELECT DISTINCT idPlayer2 AS idPlayer FROM Matchs WHERE date>"2019-04-19" ) 
```

#Database
```
php bin/console doctrine:mapping:import 'App\Entity' annotation --path=src/Entity --filter=Rankingpos
// generates getter/setter methods
php bin/console make:entity --regenerate App
```
