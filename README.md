# Build a SPA or SSR web application with phalcon, vue.js(nuxt.js) and centrifugo.
# Not for production!
Boilerplate for developing web applications

# Used technologies
* [Phalcon micro application](https://docs.phalconphp.com/hu/3.2/application-micro)
* [Vue.js frontend javascript framework](https://vuejs.org/)
* [Paseto for authentication](https://paseto.io/)
* [Centrifugo for real-time messaging](https://github.com/centrifugal/centrifugo)
* [Easy-to-use PDO wrapper for PHP projects](https://github.com/paragonie/easydb)
* [Phinx for migrations](https://phinx.org/)
* [Robo task runner](https://robo.li/)
* [Element-UI Vue 2.0 based component library](http://element.eleme.io/#/en-US/)
* [Vue 2.0 minimal admin template](https://github.com/PanJiaChen/vueAdmin-template)
* Docker

## How to install with Docker
* Install docker & docker-compose
* Clone this repository
* Download robo.phar(https://robo.li/), composer.phar(https://getcomposer.org/) in "phalcon/" folder
* Copy all .env.example files(folders "./", "phalcon/") to .env
* Fill .env files with appropriate information
* Centrifugo to work correctly, you need to fill in the address and port in "./docker/nginx/sites/centrifugo.conf line 5"
* In docker command line type "docker-compose up -d --build"
* Go in php container with command "docker exec -it php_  /bin/bash"
* Run "composer install". After installation type command "php vendor/bin/phinx migrate" to create migrateions
* Then type "php vendor/bin/phinx seed:run" to seed tables in database
* Create  paseto auth key with command "php robo.phar paseto", and "exit" from php_ container.
* Go to directory ./vue and install npm packages with "npm install".
* After installation type "npm run dev". Browser starts automatically with working application.
