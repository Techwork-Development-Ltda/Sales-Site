# Laravel-skeleton

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Octane](https://img.shields.io/badge/Octane-Enabled-FF2D20?logo=laravel&logoColor=white)
![Swoole](https://img.shields.io/badge/Swoole-Ready-4479A1?logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-PHPUnit-3776AB?logo=php&logoColor=white)
![JWT](https://img.shields.io/badge/Auth-JWT-000000?logo=jsonwebtokens&logoColor=white)
![Redis](https://img.shields.io/badge/Cache-Redis-DC382D?logo=redis&logoColor=white)
![Swagger](https://img.shields.io/badge/API-Swagger-85EA2D?logo=swagger&logoColor=black)

> Esqueleto de projeto laravel utilizando octane + swoole com rotas de autenticação + usuarios bem definidas + testes funcionais e unitarios mockery e provider como exemplo.  

> ⚠️ **IMPORTANTE**: Este é um projeto skeleton/boilerplate configurado para **ambiente de desenvolvimento**. 
> As configurações de segurança estão simplificadas para facilitar o setup inicial.
> **Não use estas configurações em produção sem as devidas alterações de segurança.**

---

## 🎯 Sobre este projeto

Este skeleton Laravel fornece uma base sólida para desenvolvimento de aplicações web e APIs RESTful, incluindo:

- ✅ **Arquitetura em camadas** (Controllers, Services, Models, Requests)
- ✅ **Autenticação dual** - Session (web) e JWT (API) com guards separados
- ✅ **Separação de rotas por camada** - Camadas de web e API para organização de projeto
- ✅ **Alta performance** com Laravel Octane + Swoole
- ✅ **Testes unitários e de integração** com Mockery e PHPUnit
- ✅ **Cache distribuído** com Redis
- ✅ **Documentação automática** com Swagger/OpenAPI
- ✅ **Observabilidade** com OpenTelemetry
- ✅ **Dependency Injection** e Service Providers
- ✅ **Validações customizadas** e Exception Handling
- ✅ **Ambiente dockerizado** pronto para uso

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
   ├─ storage/               # Documentação swagger
   ├─ tests
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
php artisan octane:start --server=swoole --host=0.0.0.0 --port=9000
```


## ▶️ Desenvolvendo com SWOOLE
> O Swoole executa aplicações PHP em um **runtime persistente escrito em C**, mantendo o código carregado em memória e evitando o bootstrap do Laravel a cada requisição.
> Isso traz ganhos significativos de performance, porém exige atenção durante o desenvolvimento, pois alterações no código **não são recarregadas automaticamente** por padrão.
> Se estiver em ambiente de desenvolvimento e precisar refletir alterações no código, utilize **uma das opções abaixo**.

### 1.

Entre no container com:

```bash
docker exec -it laravel11-skeleton bash
```

Recarregue os workers do octane.

```bash
php artisan octane:reload
```

### 2.

Rode o projeto locamente utilizando --watch (O docker não funciona o --watch corretamente, depende de eventos de filesystem).

```bash
php artisan octane:start --server=swoole --host=0.0.0.0 --port=9000 --watch
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

---

## 🔒 Checklist de Segurança para Produção

Este skeleton usa configurações simplificadas para desenvolvimento. **Antes de deployar em produção**, certifique-se de:

- [ ] Mover todas as credenciais para variáveis de ambiente (`.env`)
- [ ] Configurar senha forte para o usuário root do MySQL
- [ ] Alterar a senha do Redis (`REDIS_PASSWORD` no `.env`)
- [ ] Configurar `APP_DEBUG=false` no `.env`
- [ ] Gerar chaves fortes (`APP_KEY` e `JWT_SECRET`)
- [ ] Configurar HTTPS/TLS
- [ ] Revisar permissões de arquivos e diretórios
- [ ] Configurar CORS adequadamente
- [ ] Implementar rate limiting nas rotas de API
- [ ] Revisar e atualizar dependências (`composer update`)
- [ ] Configurar backups automáticos do banco de dados
- [ ] Implementar logs de auditoria
- [ ] Remover ou proteger a rota `/api/documentation` do Swagger

---


## Links das aplicações

- [http://localhost:8020/](http://localhost:8020/) Pagina Web Latavel
- [http://localhost:8020/api/documentation](http://localhost:8020/api/documentation) Swagger
