#Install
```
php composer.phar install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
```

#Prod
```

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

php composer.phar update


php composer.phar require symfony/apache-pack

php bin/console cache:clear
```

if the copy does not work, try without https.

on the server => 
/usr/local/php74/bin/php composer.phar update
update composer.json with the correct version of php
nano composer.json


#PROD ENABLING SWAP
https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors

free -m

total used free shared buffers cached
Mem: 2048 357 1690 0 0 237
-/+ buffers/cache: 119 1928
Swap: 0 0 0

To enable the swap you can use for example:

/bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
/sbin/mkswap /var/swap.1
/bin/chmod 0600 /var/swap.1
/sbin/swapon /var/swap.1



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
// FIRST SAVE the src/Entity file to check the differences after generation (git diff). 
```
// generate the Entity file based on the database
php bin/console doctrine:mapping:import 'App\Entity' annotation --path=src/Entity --filter=Matchs
// generates getter/setter methods
php bin/console make:entity --regenerate App
```
warning: 



#
sudo apt install php8.2-mysql
sudo apt install php8.2-xml

### COMMANDS ###

php bin/console app:generate-tennis-single-rankings YYYY-MM-DD