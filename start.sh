    
#!/bin/bash -e

red=$'\e[1;31m'
green=$'\e[1;32m'
white=$'\e[0m'

source ./src/.env

echo " $red <<<<<< Setting up Docker Environment >>>>>> $white "
docker-compose down && docker-compose up --build -d

vendor_present() {
  [ -d /var/www/html/vendor ]
}

  echo "Installing/Updating Lumen dependencies (composer)"
  if ! vendor_present; then
    # composer install
    docker exec ${APP_NAME}_php composer install
    echo "Dependencies installed"
  else
    # composer update
    docker exec ${APP_NAME}_php composer update
    echo "Dependencies updated"
  fi


