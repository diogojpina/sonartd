Clone este repositório.

Crie um virtual host no Apache:
http://framework.zend.com/manual/2.0/en/user-guide/skeleton-application.html#virtual-host

Habilite o virtual server:
sudo a2ensite <nome do vhost>

Reinicie o Apache.
sudo service apache2 restart

Configurando o Banco de Dados
Altere o arquivo: config/autoload/doctrine.global.php

Crie uma pasta:
data/DoctrineORMModule/Proxy

Defina permissão de escrita para o apache.
