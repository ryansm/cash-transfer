# Cash Transfer

API de transferência de dinheiro entre usuários.

## Introdução

Este projeto foi desenvolvido com o framework [Laravel 8](https://laravel.com/docs/8.x).

## Requisitos

-   PHP >= 7.1
-   [Composer](https://getcomposer.org/)
-   [Docker](https://docs.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   [Make](https://www.gnu.org/software/make/) (opcional)

## Instalação

1. Clone o repositório

```
git clone git@github.com:ryansm/cash-transfer.git
```

2. Vá até a raiz do projeto

```
cd cash-transfer
```

3. Instale as dependências

```
composer install
```

4. Crie o arquivo com as variáveis de ambiente

```
cp .env.example .env
```

5. Levante o ambiente

```
docker-compose up -d --build
```

6. Execute as migrations do banco

```
docker-compose exec php-fpm php artisan migrate
```

7. Inicie o processamento de filas do Laravel

```
docker-compose run --rm php-fpm php artisan queue:work
```

## Endpoints

-   [POST] http://localhost:8000/oauth/token
-   [GET] http://localhost:8000/api/user
-   [GET] http://localhost:8000/api/user/{id}
-   [POST] http://localhost:8000/api/user
-   [PUT] http://localhost:8000/api/user/{id}
-   [DELETE] http://localhost:8000/api/user/{id}
-   [POST] http://localhost:8000/api/transaction
