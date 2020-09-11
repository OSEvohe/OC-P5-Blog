# OC-P5-Blog

First PHP blog developed from scratch for OpenClassrooms project.

[![SonarCloud](https://sonarcloud.io/images/project_badges/sonarcloud-white.svg)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)

[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=alert_status)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=security_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)  
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=bugs)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=code_smells)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P5-Blog&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P5-Blog)

Code Climate  
[![Maintainability](https://api.codeclimate.com/v1/badges/567d2e65a226a923bc6f/maintainability)](https://codeclimate.com/github/OSEvohe/OC-P5-Blog/maintainability)
  
 
## Requirement
* Apache 2.4+ with mod_rewrite enabled
* Mysql
* Php 7.3+ with yaml module enabled
* Composer
* PhpMyadmin (for easy installation)
* SMTP server accessible from the machine hosting the blog

## Installation
1. Clone or download the repository
2. Upload the downloaded content to your web server.
3. In the project directory run : `composer install`
4. Import the `OCP5BLOG.sql` file into your database
5. Copy `config/config-example.yml` to `config/config.yml`, edit it to fit your needs
6. Copy `config/auth-config-example.yml` to `config/auth-config.yml`, enter a secret key (see below) 
7. Copy `config/db-config-example.yml` to `config/db-config.yml`, edit it with your Mysql credentials
8. Configure a new Virtual host in apache configuration with `public/` as DocumentRoot (see below)
9. Congratulation the Blog is working now you can customize it by changing your password and edit your profile.

## Secure your blog
### Admin password
By default, you log as admin with 'admin@my.blog/passw0rd'  
To change this, go to the admin page (link in the footer), then go to the users section and edit admin user.  
Enter a valid email and a new password.
### Secret key
A secret key stored in `config/auth-config.yml` is used to identify the user logged in, enter a long phrase with random character ie : blj@rcx44y5e&^*7z31_aj)gy@ns3$4m!%k0=&fch^&tnw--7


## Troubleshooting
**Site is not working (blank page or without any css style applied)**  
Make sure the DocumentRoot of your Virtual Host is the `public` directory.  
Tips : If the server is running on your local machine, you can set a Virtual Host with 127.0.0.x as ServerName (ie : 127.0.0.2)

exemple of Virtual Host configuration :

    <VirtualHost *:80>
        ServerName 127.0.0.2
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/OC-P5-Blog/public

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    
    <Directory "/var/www/html/OC-P5-Blog/public">
        AllowOverride All
    </Directory>


This will make the site accessible at : http://127.0.0.2  
  
**There is a warning about a file `Serializer` not writtable**  
Sometime composer don't make writable a directory used by a module.  
You need to make writable this directory : `/vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer`.

**The home page is working, but I get an apache error "Not Found" on others page**  
Check if mod_rewrite is enabled, make sure the virtual host has the directive `AllowOverride All`.

**Cannot change the Photo or CV**  
Make sure `public/uploads/` is a writable directory