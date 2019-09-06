    
#!/bin/bash -e

red=$'\e[1;31m'
green=$'\e[1;32m'
blue=$'\e[1;34m'
white=$'\e[0m'

source .env

echo " $red <<<<<< Setting up Docker Environment >>>>>> $white "
# docker-compose down && docker-compose up --build -d
#docker-compose down && docker-compose up --build -d && docker-compose logs -f
docker-compose down && docker-compose up --build -d

echo " $grn <<<<<< Installing Dependencies >>>>>> $blu "
#Sleep for 150seconds
# sudo sleep 150s 

vendor_present() {
  #[ -d /var/www/html/"${PWD##*/}"/vendor ]
   [ -d vendor ]
}

  echo " $red <<<<<< Installing/Updating Lumen dependencies (composer) >>>>>> $white "
  if ! vendor_present; then
    composer install
    echo " $red <<<<<< Dependencies installed >>>>>> $white "
  else
    composer update
    echo " $red <<<<<< Dependencies updated >>>>>> $white "
  fi

# docker exec ${APP_NAME}_php bash -c 'chmod 777 -R /var/www/html'
# docker exec ${APP_NAME}_php bash -c 'chmod 777 -R storage'
# docker exec -it myorders_app bash -c "-u devuser /bin/bash"

echo " $red <<<<<< Running Migrations & Data Seeding >>>>>> $white "
# docker exec ${APP_NAME}_php php artisan key:generate
docker exec ${APP_NAME}_php php artisan migrate
# docker exec ${APP_NAME}_php php artisan db:seed

# php artisan key:generate
# php artisan migrate
# php artisan db:seed
docker exec ${APP_NAME}_php php artisan db:seed

echo " $red <<<<<< Running PHP in-built server >>>>>> $white "
# docker exec ${APP_NAME}_php php -S localhot:8080 -t public
# php -S localhost:8080 -t public

echo " $red <<<<<< Running MyOrder All Test Cases >>>>>> $white "
docker exec ${APP_NAME}_php ./vendor/bin/phpunit
# ./vendor/bin/phpunit

echo " $red <<<<<< Running MyOrder Integration Test Cases >>>>>> $white "
docker exec ${APP_NAME}_php ./vendor/bin/phpunit OrderIntegrationTest
# ./vendor/bin/phpunit OrderIntegrationTest

echo " $red <<<<<< Running MyOrder Unit Test Cases >>>>>> $white "
docker exec ${APP_NAME}_php ./vendor/bin/phpunit OrderControllerTest
# ./vendor/bin/phpunit OrderControllerTest

echo " $red <<<<<< Wanna check Swagger Implemetatipn >>>>>> $white "
echo "http://localhost:8080/swagger/" 
