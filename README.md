# Projet de calculatrice RPN Rest API + SWAGER avec Symfony 5.4

## le guide d'installation
1-composer install

2- php bin/console doctrine:database:create

3- php bin/console make:migration

4- php bin/console doctrine:migrations:migrate

5- pour charger liste des operations :
* php bin/console doctrine:fixtures:load --append
