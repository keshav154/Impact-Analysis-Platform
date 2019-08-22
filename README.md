# Impact Analysis Platform
Veative WebVR is a an offering of 12 interactive, STEM modules, with assessment. The assessment part feeds into our analytics portal which tracks and stores student usage and activity data. This data provides an instructor with useful information such as which question was answered correctly, the knowledge and cognitive domain of that question, and so on. There are 2 other tabs to access, which will show the overall, global Score by Module (to see how easy or difficult a module may be) and Modules Attempted, which can point to engagement. In all, we are not only trying to bring an immersive experience for students, but hopefully supporting the efforts of teachers to better understand how their students are engaging with the material.

## Technology Used

- Zend Framework 2.0 with Zend Apigility
- MySql 5.x
- PHP 5.x
- Google Chart, jQuery & Bootstarp

## Demo

[Impact Analysis Platform](http://ec2-52-5-117-32.compute-1.amazonaws.com/unicef/public/report)

## Instruction to install the application

- Download the project from GIT
```
git clone https://github.com/Veativetech/Impact-Analysis-Platform.git
```
- Place this project inside htdocs folder
- Open http://localhost/phpmyadmin in URL and create database "unicef_db"
- Import SQL file "unicef_db.sql" in PhpMyAdmin
- Find sample data SQL file "sample-data.sql" in root folder

## Database configuration

- Configure the database connection in this mention file path which is /config/autoload/production/global.php
- Change the database name, db username and db password.


## Dependency

- [VeativeWebVR](https://github.com/Veativetech/VeativeWebVR/)

## License

This program is free software for both commercial and non-commercial use, distributed under the [MIT License](https://github.com/Veativetech/Impact-Analysis-Platform/blob/master/LICENSE.txt).
