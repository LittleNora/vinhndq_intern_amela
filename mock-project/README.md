# Lưu ý

- **Để chạy được dự án, cần cài đặt Docker và Docker Compose**

# Khởi động dự án

```shell
docker-compose up -d
```

# Tắt dự án

```shell
docker-compose down
```

# Cài dependencies (composer install, npm install,...)

### MacOS + Linux

```shell
docker run --rm -v $(pwd):/app -w /app prooph/composer:8.2 install

docker run --rm -v $(pwd):/app -w /app prooph/composer:8.2 dump-autoload
```

## Nếu bạn đang dùng Windows thì các command trên sẽ như sau:

### Với Git bash

```shell
docker run --rm -v "/$(pwd)":/app -w //app prooph/composer:8.2 install

docker run --rm -v "/$(pwd)":/app -w //app prooph/composer:8.2 dump-autoload
```

### Với PowerShell

```shell
docker run --rm -v "$(pwd):/app" -w /app prooph/composer:8.2 install

docker run --rm -v "$(pwd):/app" -w /app prooph/composer:8.2 dump-autoload
```

### Với Command Prompt

```shell
docker run --rm -v "%cd%:/app" -w /app prooph/composer:8.2 install

docker run --rm -v "%cd%:/app" -w /app prooph/composer:8.2 dump-autoload
```

# Tạo file .env, generate key, migrate database, seed database, generate jwt secret

```shell
docker compose exec app sh

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan jwt:secret

exit
```
