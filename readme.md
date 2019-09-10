# MyOrderAPI
This is REST API for creating a new order, updating a order and get order list.

## Tech Stack used...

- [Php v7.3](https://php.net/) to develop backend support.
- [MySQL v5.7](https://mysql.com/) as the database.
- [Lumen v5.8](https://lumen.laravel.com/docs) Lumen is a micro web framework written in PHP, created by Laravel.
- [Docker](https://www.docker.com/) as the container service to isolate the environment.
- [Apache 2](https://httpd.apache.org/) as web server
- [PHPUnit](https://github.com/sebastianbergmann/phpunit) for Unit and Integration Testing
- [Swagger 3.0](https://github.com/DarkaOnLine/SwaggerLume) Swagger is an open-source software framework backed by a large ecosystem of tools that helps developers design, build, document, and consume RESTful Web services.



## How to Use (With Docker and start.sh script)
### *NOTES: Before running with Docker, it assumes that Docker environement pre-installed in your system.
1). Clone GIT repository in your desired directory..

``` bash
git clone REPOSITORY
```

2). Check .env file, if you want to change environment credentials in .env file according to your environment

**.env**
``` bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DBNAME
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD

GOOGLE_MAP_KEY=
```
3). Set Google Map API key in .env file

``` bash
GOOGLE_MAP_KEY=
```

4). Now open Command Line And Run start.sh shell script **

``` bash
bash start.

OR

./start.sh
```

## How to Use (Without Docker and start.sh script - Run Application Manually)
### *NOTES: Before run manually, it assumes that PHP, MySQL and Composer are pre-installed in your system.

1). Clone GIT repository in your desired directory..

``` bash
git clone REPOSITORY
```

2). Check .env file, if you want to change environment credentials in .env file according to your environment

**.env**
``` bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DBNAME
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD

GOOGLE_MAP_KEY=
```

3). Set Google Map API key in .env file

``` bash
GOOGLE_MAP_KEY=
```

4). Now open Command Line and got to project directory and Run below commands in CLI**

``` bash
cd REPO_NAME_PATH
composer update
php artisan migrate
php artisan db:seed
php -S localhost:8080 -t public
```



## Swagger OpenAPI 3.0 integration

1. Open URL `http://localhost:8080/swagger/` for API documenatation
2. You can perform GET, POST AND PATCH /order API

## Code-coverage - go to below URL

`http://localhost:8080/code-covergae/`


#### Running Test cases...

## To Perform all test cases
With Docker
``` bash
docker exec myorders_php ./vendor/bin/phpunit
```
Without Docker
``` bash
./vendor/bin/phpunit
```

## To Perform Unit test cases
With Docker
``` bash
docker exec myorders_php ./vendor/bin/phpunit ./tests/Unit
```
Without Docker
``` bash
./vendor/bin/phpunit ./tests/Integration
```



## To Perform Integration test cases
With Docker
``` bash
docker exec myorders_php ./vendor/bin/phpunit ./tests/Unit
```
Without Docker
``` bash
./vendor/bin/phpunit ./tests/Integration
```

## Api Endpoint Reference Documentation


#### Place order

  - Description: Create/Post a new Order.
  - Method: `POST`
  - URL path: `http://localhost:8080/orders`
  - URL endpoint: `/orders`
  - Content-Type: `application/json`
  - Request body:

    ```
    {
        "origin": ["START_LATITUDE", "START_LONGTITUDE"],
        "destination": ["END_LATITUDE", "END_LONGTITUDE"]
    }
    ```
    - Example
    ```
    {
        "origin": ["28.644800", "77.308601"],
        "destination": ["19.076090", "72.877426"]
    }
    ```

  - Response:

    Header: `HTTP 200`
    Body:
      ```
      {
          "id": <order_id>,
          "distance": <total_distance>,
          "status": "UNASSIGNED"
      }
      ```
    or

    Header: `HTTP <HTTP_CODE>`
    Body:

      ```
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```
      ```
        Code                    Description
        - 200                   successful operation
        - 400                   Bad Request
        - 422                   Request Body Validation Error
        - 405                   Method Not Allowed
        - 500                   Internal Server Error    


#### Take order

  - Description: Update/take a new Order.
  - Method: `PATCH`
  - URL path: `http://localhost:8080/orders/:id`
  - URL endpoint: `/orders/:id`
  - Content-Type: `application/json`
  - Request body:
    ```
    {
        "status": "TAKEN"
    }
    ```
  - Response:
    Header: `HTTP 200`
    Body:
      ```
      {
          "status": "SUCCESS"
      }
      ```
    or

    Header: `HTTP <HTTP_CODE>`
    Body:
      ```
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```

      ```
        Code                    Description
        - 200                   successful operation
        - 400                   Bad Request
        - 405                   Method Not Allowed
        - 422                   Validation Error
        - 406                   Invalid ID
        - 409                   Order Already Taken
        - 500                   Internal Server Error    


#### Order list

  - Description: List/get Order List.
  - Method: `GET`
  - URL path: `http://localhost:8080/orders`
  - URL endpoint: `/orders`
  - Content-Type: `application/json`
  - Response:
    Header: `HTTP 200`
    Body:
      ```
      [
          {
              "id": <order_id>,
              "distance": <total_distance>,
              "status": <ORDER_STATUS>
          },
          ...
      ]
      ```

    or

    Header: `HTTP <HTTP_CODE>` Body:

    ```
    {
        "error": "ERROR_DESCRIPTION"
    }
    ```

    ```
    Code                    Description
    - 200                   Successful operation
    - 400                   Bad Request
    - 422                   Validation Error
    - 500                   Internal Server Error    

## Credits

- [Abhinav Gupta](https://github.com/i-abhinav)

- For Docker Implementation get help from
(https://github.com/laravel-101/Laravel-Docker-Template)

- For Swagger Integartion get help from
(https://github.com/DarkaOnLine/SwaggerLume)

- Unit Testing - Thanks you Jeffrey Way
(https://code.tutsplus.com/tutorials/testing-laravel-controllers--net-31456)

- And obviously (Stack Overflow)
(https://stackoverflow.com)