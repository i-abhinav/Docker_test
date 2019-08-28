    
#!/bin/bash -e

red=$'\e[1;31m'
green=$'\e[1;32m'
blue=$'\e[1;34m'
white=$'\e[0m'


echo " $red ----- Installing Pre requisites ------- $white "
sudo docker-compose down && docker-compose up --build -d

fresh_container() {
  [ ! -f $INIT_SEM ]
}

app_present() {
  [ -f /app/config/database.php ]
}

vendor_present() {
  [ -f /app/vendor ]
}

wait_for_db() {
  local db_host="${DB_HOST:-mariadb}"
  local db_port="${DB_PORT:-3306}"
  local db_address=$(getent hosts "$db_host" | awk '{ print $1 }')
  counter=0
  log "Connecting to mariadb at $db_address"
  while ! curl --silent "$db_address:$db_port" >/dev/null; do
    counter=$((counter+1))
    if [ $counter == 30 ]; then
      log "Error: Couldn't connect to mariadb."
      exit 1
    fi
    log "Trying to connect to mariadb at $db_address. Attempt $counter."
    sleep 5
  done
}

setup_db() {
  log "Configuring the database"
  sed -i "s/utf8mb4/utf8/g" /app/config/database.php
  php artisan migrate --force
}


  log "Installing/Updating Lumen dependencies (composer)"
  if ! vendor_present; then
    composer install
    log "Dependencies installed"
  else
    composer update
    log "Dependencies updated"
  fi

  wait_for_db

  if ! fresh_container; then
    echo "#########################################################################"
    echo "                                                                         "
    echo " App initialization skipped:                                             "
    echo " Delete the file $INIT_SEM and restart the container to reinitialize     "
    echo " You can alternatively run specific commands using docker-compose exec   "
    echo " e.g docker-compose exec myapp php artisan make:console FooCommand       "
    echo "                                                                         "
    echo "#########################################################################"
  else
    setup_db
    log "Initialization finished"
    touch $INIT_SEM
  fi
fi

exec tini -- "$@"