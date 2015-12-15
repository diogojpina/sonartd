Clone este repositório.

Crie um virtual host no Apache:
http://framework.zend.com/manual/2.0/en/user-guide/skeleton-application.html#virtual-host

Entre na pasta que você clonou e execute o seguinte comando para baixar as dependencias:
php composer.phar install

Habilite o módulo de rewrite:
sudo a2enmod rewrite

Habilite o virtual server:
sudo a2ensite <nome do vhost>

Reinicie o Apache.
sudo service apache2 restart

Configurando o Banco de Dados
Altere o arquivo: config/autoload/doctrine.global.php

Crie uma pasta:
data/DoctrineORMModule/Proxy

Defina permissão de escrita para o apache.

*Obs.: Você precisa instalar php5-mysql php5-curl
