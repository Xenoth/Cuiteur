#Projet Cuiteur
Université de Franche Comté 2015-2016 (Enseignant François Piat)

##Description
Website Twitter like realized as project at University for Web's language Introdroduction

##Requirements
Cuiteur was created using PHP5, HTML5 and use a Database Supplies as SQL format

##Install
If you want try this website you'll need to install a local Server LAMP for Linux, or WAMP for Windows

###Linux Installation
First install all package required :
```
sudo apt install apache2 php mysql-server libapache2-mod-php php-mysql
```
Check if your Server is well installed using the URLs http://localhost or http://127.0.0.1 (You should see a "It Works!" page)

Configue your server and mySQL if required, now you can put the whole project cuiteur on the Directory /var/www/html/

Don't forget to import the Database, you can use PHPmyAdmin to importe easily the given .SQL file on root
```
sudo apt-get install phpmyadmin
```

##Authors
* BAILLEUX Pol (PHP Master) 
* ROBLES Caroline (HTML/CSS Master)
* PIAT François (Teacher, Subject, DataBase Conception)


##Missing features
* encrypting URL
* Avatar's Upload
* passwords's Hashing
