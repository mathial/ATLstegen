#Install
```
php composer.phar install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
```

#Prod
```
php composer.phar require symfony/apache-pack
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

#Database
```
php bin/console doctrine:mapping:import 'App\Entity' annotation --path=src/Entity --filter=Rankingpos
// generates getter/setter methods
php bin/console make:entity --regenerate App
```
