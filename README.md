K12 - Cloud and Web application
=======================

Introduction
------------
This is a back-end for the student app and a web interface for user with higher privileges. 

Installation
------------

Using Composer (recommended)
----------------------------
Clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/raffysommy/K12-Api
    cd K12-Api
    php composer.phar self-update
    php composer.phar install

Web Server Setup
----------------
### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName k12api
        DocumentRoot /path/to/k12api/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/k12api/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
