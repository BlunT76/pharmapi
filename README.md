# Symfony

## installation

- installer le cli  
`wget https://get.symfony.com/cli/installer -O - | bash`
- creer un nouveau projet  
  - `symfony new pharmapi`  
  - `cd pharmapi`
- pour lancer le serveur de dev  
`symfony server:start`
- installer ces bundles (equivalent des packages npm)
  - `composer require symfony/maker-bundle --dev`
  - `composer require orm sensio/generator-bundle symfony/serializer-pack`

## Utilisation
- creer la database sqlite( paquet php sqlite3 necessaire)  
`php bin/console doctrine:database:create`
- creer un entity (model), laisser vous guider  
`php bin/console make:entity`
- creer la migration du model automatiquement  
`php bin/console make:migration`
- lancer la migration  
`php bin/console doctrine:migrations:migrate`
- creer un controller  
`php bin/console make:controller PharmacieController`

## En cas d'erreur
Le cli de symfony indique les erreurs et propose des solutions. C'est plutot bien fait. Profitez en !!