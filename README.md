<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p>Проект представляет собой backend-платформу интернет-магазина, обеспечивающую работу каталога, корзины, оформление заказов, управление пользователями и выполнение административных операций.</p>
<p>Админ-панель построена на Backpack и позволяет создавать, редактировать и удалять ключевые сущности проекта.</p>
<p>Клиентские приложения взаимодействуют с системой через REST API с разграничением прав и аутентификацией пользователей с использованием Laravel Sanctum.</p>

## Используемые технологии

### Backend
- **PHP 8.3.15** — серверный язык
- **Laravel 12.37.0** — основной фреймворк
- **Laravel Sanctum** — аутентификация и защита API
- **Backpack for Laravel** — административная панель
- **MariaDB 10.4** — база данных

## Установка

### 1. Установка зависимостей
```bash
composer install
```

### 2. Настройка окружения

```bash
cp .env.example .env
```

### 3. Генерация ключа приложения

```bash
php artisan key:generate
```

### 4. Создание символьной ссылки для хранилища
```bash
php artisan storage:link
```
Это создаст `public/storage`, который будет ссылаться на `storage/app/public`.

### 5. Настройка базы данных
Укажите параметры подключения к базе в файле `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### 6. Миграции и сиды
```bash
php artisan migrate --seed
```

### 7. Запуск локального сервера
```bash
php artisan serve
```
