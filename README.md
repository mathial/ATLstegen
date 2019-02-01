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
/Applications/MAMP/bin/startMysql.sh
```

#Database
```
php bin/console doctrine:mapping:import 'App\Entity' annotation --path=src/Entity --filter=Rankingpos
// generates getter/setter methods
php bin/console make:entity --regenerate App
```
