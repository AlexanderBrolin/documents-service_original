#### Установка

```
git clone https://github.com/dmitriy-uvin/documents-service
cd documents-service
composer install
npm install && npm run dev
```
```
cp .env.example .env
Setup database configuration
Setup DBrain api tokens
php artisan key:generate
php artisan storage:link
```
```
php artisan migrate
php artisan db:seed
```
Будут созданы 4 пользователя с разными ролями:
- Developer<br>
Login: developer@developer.com<br>
Password: password
- Administrator<br>
Login: admin@gmail.com<br>
Password: 123123123
- Manager<br>
Login: manager@gmail.com<br>
Password: 123123123
- Worker<br>
Login: worker1@gmail.com<br>
Password: 123123123
