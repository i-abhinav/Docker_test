# Testing Docker
For Test purpose - Apache2 + PHP7.3 + MySQL5.7 + Lumen Frmaework 5.8 Environemnt in  Docker
## Tech Stack used...


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

```

3). Now open Command Line And Run start.sh shell script **

``` bash
bash start.

OR

./start.sh
```