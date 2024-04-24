* Manual de criação para servidor de bloqueio de dominios. 

* No final de tudo é só acessar e adicionar os dominios linha por linha podemos ser um ou varios dominios ao mesmo tempo desde que seja linha por linha.


## Criar Banco de dados MySQL
```
# mysql -u seu_usuario -p

CREATE DATABASE IF NOT EXISTS censura;

USE censura;

CREATE TABLE IF NOT EXISTS dominios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dominio VARCHAR(255) UNIQUE
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

```
## Criar usuario e senha para acesso ao site
```
# mysql -u seu_usuario -p
INSERT INTO usuarios (nome_usuario, senha) VALUES ('seu_nome_usuario_AQUI', 'sua_senha_AQUI');
```

```
cd /tmp
git clone https://github.com/joandson19/Server_bloqueio-de-Dominios
cd Server_bloqueio-de-Dominios
mv censura /var/www/html
```

## Acesse o arquivo de configurações e inclua seus dados do banco de dados MySQL
```
cd /var/www/html/censura/conf
nano bd.conf
```

# No Unbound ou no Bind
## Coloque o arquivo python no seu servidor de dns conforme seja bind9 ou unbound e após crie uma uma rotina na cron para baixar a lista e adicionar a rpz.


