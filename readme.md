<p align="center" style="background-color: #81E5A3; padding: 2rem">
  <img src="https://yappix.ru/wp-content/uploads/2019/12/logo.png">
</p>

## Requirements
- composer 1.10.6+
- <ins>node v12.18.2</ins>
- npm 6.14.6

## Структура папок
```sh
/ycms-apps
/ycms-client-panel
/ycms-ionic-prototype
/ycms-ionic-service
```


## Install
Inside ycms-client-panel
```sh
npm i
composer install
npm run dev
php artisan migrate:fresh
php artisan db:seed
sudo chmod -R 775 storage/
sudo chown $USER:www-data -R storage/
mkdir public/img/uploads
sudo chmod -R 775 public/img/uploads/
sudo chown $USER:www-data -R public/img/uploads/
```

to apply shop units

```sh
php artisan db:seed --class=ShopUnitSeeder
php artisan db:seed --class=ShopUnits2Seeder
```

sending  email 

```sh
example for gmail (give access to insecure app)

.env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=XXXXXX@gmail.com
MAIL_PASSWORD=PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=XXXXXX@gmail.com
MAIL_FROM_NAME=yappix

config/mail.php
'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'XXXXXX@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'yappix'),
    ],

    
```

Inside browser  
Ctrl + F5 =)


## To Develop
```sh
npm run watch
# laravel-echo-server start # TODO is this relevant ?
# php artisan queue:work
```


