# Calculator

Como efetuar o deploy:

- Faça o clone do projeto do GitHub

- No diretório do projeto execute o comando

        composer install

- Criar uma base de dados em um servidor MySQL

- Copie o arquivo .env.example para .env

        cp .env.example .env
        
- Configure os parâmetros do banco de dados criado no arquivo .env
        
        1) DB_HOST
        2) DB_PORT
        3) DB_DATABASE
        4) DB_USERNAME
        5) DB_PASSWORD

- Crie uma Key para a aplicação com o comando

        php artisan key:generate

- Após a base configurada acessar o terminal no diretório do projeto e executar o comando abaixo para criar a tabela
        
        php artisan migrate
        
- Para inciar o webserver executar o comando
    
        php artisan server
        
O projeto funciona no Apache também, para isso copie o projeto para o document root 
