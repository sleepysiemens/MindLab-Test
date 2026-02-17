Инструкция для запуска:

В корневой папке проекта открыть терминал, запустить команды

`docker compose up -d`

`docker compose exec app composer install`

`cp src/.env.example src/.env`

`docker compose exec app php artisan key:gen`

`docker compose exec app php artisan jwt:secret`

`docker compose exec app php artisan migrate --seed`

После этих команд получаем запущенное приложение по адресу http://localhost:8080

Документация для API доступна по ссылке https://documenter.getpostman.com/view/31990872/2sBXcDF1JC
