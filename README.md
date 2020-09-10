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
* Php 7.3+
* Composer
* PhpMyadmin (for easy installation)

## Installation
1. Clone or download the repository
2. Upload the downloaded content to the root of your server or use a virtual host
3. In the project root directory run : `composer install`
4. Import the `OCP5BLOG.sql` file into your database
5. Import the `OCP5BLOG_Dataset` file into your database
6. Copy `config/config-example.yml` to `config/config.yml`, edit it to fit your needs
7. Copy `config/auth-config-example.yml` to `config/auth-config.yml`, enter a secret key (see below) 
8. Copy `config/db-config-example.yml` to `config/db-config`, edit it with your Mysql credentials
9. Congratulation the Blog is working

## Secure your blog
### Admin password
By default you log as admin with 'admin/passw0rd'  
To change this, go to the admin page (link in the footer), then go to the users section and edit admin user.  
You need to enter a valid email and a new password
### Secret key
A secret key stored in `config/auth-config.yml` is used to identify the user logged in, enter a long phrase with random character ie : blj@rcx44y5e&^*7z31_aj)gy@ns3$4m!%k0=&fch^&tnw--7
