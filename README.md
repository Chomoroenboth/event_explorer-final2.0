Install vendors

```sh
composer install
```

Migrate
```sh
php artisan migrate:fresh 

```sh
php artisan migrate
```

Seed


```sh
php artisan db:seed
```

Generate key

```sh
php artisan key:generate
```

Run

```sh
php artisan serve
```
```sh
php artisan storage:link
'email' => 'darith@gmail.com',
            'password' => Hash::make('123456789')

'email' => 'both@gmail.com',
            'password' => Hash::make('123456789')
'email' => 'Pichchansomanea@gmail.com',
            'password'=> Hash::make(123456789)
Route to login admin is:/admin/login