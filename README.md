## Passo a passo para rodar o projeto
Clone o projeto
```sh
git clone https://github.com/LeoMendes475/task-manager-back.git
```
```sh
cd laravel-10/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize essas variáveis de ambiente no arquivo .env
```dosini
APP_NAME="Task manager"
APP_URL=http://localhost:8000

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Instalar dependências do projeto
```sh
composer update
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)
