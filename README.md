silex-teste
===========

Teste de integração com Silex


Configurando o VirtualHost

  -httpd.conf
```
   NameVirtualHost *

   <VirtualHost *>
    ServerName lotus
    DocumentRoot e:/servidor/silex-teste/web
  </VirtualHost>
```
  -HOSTS
```
  127.0.0.1 lotus
```  
  
Configurando o Banco de dados

  -rodar script de SQL
```
    docs/doctrine/banco+data.sql
```
    
Fazer login com:
```
  user:edu / pw:foo
```  
  
  
Mano, a parte do sistema está bem zuada, tudo incompleto, estou no meio do desenvolvimento ainda.

depois de acessar
<code>http://lotus</code>
e fazer login, acesse o menu:
<code>Gerenciar Usuários > Usuários</code>
o sistema vai trazer uma tela para cadastro de usuarios, caso queira abrir a edição do usuario acesse:
<code>http://lotus/users/crud/new?idRegistro=1</code>
Ainda não é possível fazer login com um novo usuário cadastrado, pois não estou aplicando a criptografia correta na senha.
Caso façam besteira e não consigam mais fazer login, basta ir no BD e atualizar o campo senha para 
<code>5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==</code> que é 'foo' criptografada

Véi, ta zuado o bagulho eu sei...

  
  
  
  
  
