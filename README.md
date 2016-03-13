# todo

TECHNOLOGIES USED

PHP 5.6.15
MySQL V15.1 Distribution 10.1.9-MariaDB
Composer
Symfony2

INSTALLATION INSTRUCTIONS

clone https://github.com/mokgosi/currencyworkz.git 

Then cd to "currencyworkz" and run the following composer command to install vendors
    $ composer update

Then open app/config/parameters.yml and change the following to your settings
    database_host: 127.0.0.1
    database_port: 3306
    database_name: todo
    database_user: root
    database_password: null

Then run the following command to create database
    create database currencyworkz;

Then run currencyworkz.sql to create tables and populate the db with test data.

==

RUNNING & TESTING THE APP

Open console, cd to into currencyworkz folder and run the following command
    $ php app/console server:run

This will run php built-in server

Then on your browser navigate to: http://localhost:8000 - for the app client that consumes the API

Then on your browser navigate to: http://localhost:8000/api/doc - for the API documentation & sandbox

