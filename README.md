# Laravel-skeleton
> Esqueleto de projeto laravel com rotas de autenticação + usuarios bem definidas + testes funcionais e unitarios mockery e provider como exemplo.  
---

## 🗂️ Estrutura do repositório

```
/
├─ docker-compose.yml        # Orquestração dos serviços
├─ README.md                 # Este arquivo
└─ laravel/                  # Projeto Laravel #1
   ├─ app/
   ├─ bootstrap/
   ├─ config/
   ├─ database/
   ├─ routes/
   ├─ composer.json
   └─ ...
```

---

## 🛠️ Pré-requisitos

- [Docker](https://www.docker.com/)  
- [Docker Compose](https://docs.docker.com/compose/) (v2 recomendado: `docker compose`)  

Se optar por rodar o Laravel **fora do Docker**, também será necessário:  
- [PHP 8.3+](https://www.php.net/)  
- [Composer](https://getcomposer.org/)  
- MySQL 8.0 instalado e configurado  

---

## ⚙️ Configuração inicial

Preencha o COMPOSER_AUTH no docker-compose.yml com um Fine-grained personal access tokens

Na primeira vez que subir o projeto, configure o `.env`:

```bash
cd laravel
cp .env.example .env
```

Depois suba os containers:

```bash
docker compose up -d
```

---

## 🔑 Geração de chaves

Acesse o container da aplicação:

```bash
docker exec -it laravel11-skeleton bash
```

E rode:

```bash
# APP_KEY do Laravel
php artisan key:generate

# JWT_SECRET (se estiver usando JWT Auth)
php artisan jwt:secret
```

---

## 🗄️ Criação do banco de dados

> O banco é criado vazio pelo container MySQL.
> Base **laravel** deve ser criada. Base criada inicialmente no formato **utf8mb4_general_ci**. 
> **As tabelas e dados iniciais são gerados pelo Laravel** via migrations e seeders.

Ainda dentro do container `laravel11-skeleton`, execute:

```bash
# Criar tabelas
php artisan migrate

# Popular o banco com dados de seeders
php artisan db:seed
```

Ou, para recriar do zero já com seeds:

```bash
php artisan migrate:fresh --seed
```

---

## ▶️ Acessando a aplicação

- Laravel rodando: [http://localhost:8020](http://localhost:8020)  
- MySQL: `localhost:3306` (usuário root, sem senha)

---

## 🧩 Comandos úteis

Dentro do container `laravel11-skeleton`:

```bash
# Instalar dependências
composer install

# Limpar caches
php artisan cache:clear
php artisan config:clear

# Rodar servidor embutido (já configurado no docker-compose)
php artisan serve --host=0.0.0.0 --port=9000
```

---

## ✅ Checklist rápido

- [ ] Clonar o repo  
- [ ] `cp laravel/.env.example laravel/.env`  
- [ ] Subir containers com `docker compos
e up -d`  
- [ ] Acessar container `docker exec -it laravel11-skeleton bash`  
- [ ] Gerar `APP_KEY` e `JWT_SECRET`  
- [ ] Rodar `php artisan migrate --seed`  
- [ ] Testar em [http://localhost:8020](http://localhost:8020)  

Pronto 🎉 Sua aplicação Laravel estará rodando com banco de dados populado!


## Links das aplicações

- [http://localhost:8020/](http://localhost:8020/) Pagina Web Latavel
- [http://localhost:8020/api/documentation](http://localhost:8020/api/documentation) Swagger
