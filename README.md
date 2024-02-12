
## Passo a passo para rodar o projeto

Clone o projeto
```sh
git clone https://github.com/fabiobros/api_laravel.git api_laravelo
```
```sh
cd api_laravelo/
```

Crie o Arquivo .env
```sh
cp .env.example .env
```

Suba os containers do projeto
```sh
docker-compose up -d
```

Acesse o container
```sh
docker-compose exec app bash
```

Instale as dependências do projeto
```sh
composer install
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Execute a migration
```sh
php artisan migrate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)

Caso apareça a pagina padrão do laravel, o projeto estara funcional.

Todas as rotas e documentação estão dentro do arquivo

API_LARAVEL_CRUD.postman_collection

Basta importar a collection para o postman e utilizar as rotas POST,GET,PUT e DELETE.


