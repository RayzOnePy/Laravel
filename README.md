# Laravel

Инструкция по развертыванию:
1) открыть терминал
2) создать и перейти в каталог
3) склонировать репозиторий командой (git clone https://github.com/RayzOnePy/Laravel.git)
4) добавить в файл .env строку - FILESYSTEM_DISK=public
5) перейти в каталог dockers -> dev
6) прописать команду docker-compose up -d --build
7) прописать команду docker-compose exec -it api bash
8) прописать команду composer update
9) прописать команду php artisan storage:link
10) прописать команду php artisan migrate
11) прописать команду php artisan db:seed
12) прописать команду php artisan serve
