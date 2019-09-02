    
#!/bin/bash -e

red=$'\e[1;31m'
green=$'\e[1;32m'
blue=$'\e[1;34m'
white=$'\e[0m'


echo " $red <<<<<< Setting up Docker Environment >>>>>> $white "
docker-compose down && docker-compose up --build -d

echo " $grn <<<<<< Installing Dependencies >>>>>> $blu "
sudo sleep 200s #this line is included for composer to finish the dependency installation so that test case can execute without error.

vendor_present() {
  [ -f /var/www/myorder/vendor ]
}

  echo "Installing/Updating Lumen dependencies (composer)"
  if ! vendor_present; then
    composer install
    echo "Dependencies installed"
  else
    composer update
    echo "Dependencies updated"
  fi

echo " $red <<<<<< Running Migrations & Data Seeding >>>>>> $white "
docker exec ${APP_NAME}_php php artisan key:generate
docker exec ${APP_NAME}_php php artisan migrate
docker exec ${APP_NAME}_php php artisan db:seed
# docker exec ${APP_NAME}_php php artisan db:seed

echo " $red <<<<<< Running PHP in-built server >>>>>> $white "
docker exec ${APP_NAME}_php php -S localhot:8080 -t public

echo " $red <<<<<< Running PHPUnit Test >>>>>> $white "
docker exec ${APP_NAME}_php vendor\bin\phpunit

exit 0