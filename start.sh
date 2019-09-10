    
#!/bin/bash -e

red=$'\e[1;31m'
green=$'\e[1;32m'
white=$'\e[0m'

source ./src/.env

echo " $red <<<<<< Setting up Docker Environment >>>>>> $white "
docker-compose down && docker-compose up --build -d

echo " $grn <<<<<< Installing Dependencies >>>>>> $blu "
#sleep for 150 seconds 
# sudo sleep 150s 

vendor_present() {
  [ -d /var/www/html/vendor ]
}

  echo "Installing/Updating Lumen dependencies (composer)"
  if ! vendor_present; then
    # composer install
    docker exec ${APP_NAME}_php composer install --no-progress --quiet
    echo "Dependencies installed"
  else
    # composer update
    docker exec ${APP_NAME}_php composer update --no-progress --quiet
    echo "Dependencies updated"
  fi

echo " $red <<<<<< Running Migrations & Data Seeding >>>>>> $white "
docker exec ${APP_NAME}_php php artisan migrate
docker exec ${APP_NAME}_php php artisan db:seed
# docker exec ${APP_NAME}_php php artisan db:seed

echo " $red <<<<<< Running PHP in-built server >>>>>> $white "
# docker exec ${APP_NAME}_php php -S localhost:8080 -t public

echo " $red <<<<<< Running PHPUnit Test >>>>>> $white "
docker exec ${APP_NAME}_php ./vendor/bin/phpunit

