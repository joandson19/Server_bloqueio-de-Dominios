* Manual de criação para servidor de bloqueio de dominios. 

* No final de tudo é só acessar e adicionar os dominios linha por linha podemos ser um ou varios dominios ao mesmo tempo desde que seja linha por linha.


# Criar Banco de dados MySQL
```
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

```
cd /tmp
git clone https://github.com/joandson19/Server_bloqueio-de-Dominios
cd Server_bloqueio-de-Dominios
mv censura /var/www/html
```

```
cd
