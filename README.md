# portefolio
1 use composer install
2 change at ./.env
    APP_SECRET
    DATABASE_URL
    MAILER_URL
    MAILER_URL
3 use php bin/console doctrine:database:create
4 use php bin/console make:migration
5 use php bin/console doctrine:migration:migrate

6 change variable at ./src/Controller/Admin/ContactController.php
    $secret
    ->setFrom('xxxx@gmail.com')
	->setTo('xxxx@gmail.com')