# Calculator

Como efetuar o deploy:

- Criar uma base local MySQL e adicionar os parâmetros para conexão no arquivo .env
- Após a base configurada acessar o terminal no diretório do projeto e executar o comando abaixo para criar a tabela
        
        php artisan migrate
        
- Para inciar o webserver executar o comando
    
        php artisan server
        
O projeto funciona no Apache também, para isso copie o projeto para o document root 
