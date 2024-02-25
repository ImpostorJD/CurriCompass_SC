![Hero](https://www.helpforassessment.com/blog/wp-content/uploads/2020/08/how-to-do-a-capstone-project.jpg)

[![Progress - In Development](https://img.shields.io/badge/Progress-In_Development-D30000?style=for-the-badge)]()

***
# CurriCompass

## Overview
This project entitled CurriCompass is a Capstone project in compliance with the requirements for Capstone Project (CAP1). The goal of this project is to streamline the pre-advisement process of students for the upcoming semestral enrollment. 

## Features
- Algorithm-based decision-making for pre-advisement
- Adjustable lists of courses 
- Powerful tool for defining curriculum

## Technologies Used
- Angular 17.0.2
- MaterialUI
- Laravel v 10.3.0
- php v 8.1.0
- Visual Studio Code
- GitHub
- PenPot Desktop

## Installation
NOTE: Install both package manager if you want to run both frontend and backend.

### For Frontend Developers

[![NodeJS - Installation](https://img.shields.io/badge/NodeJS-Installation-2ea44f)](https://nodejs.org/dist/v20.10.0/node-v20.10.0-x64.msi)
- Click the badge above to download the executable file of NodeJS
- Run the prompt.
- Just install the node runtime.

#### Run Frontend Client
- cd into CurriCompassUI via:
```bash
 cd ./CurriCompassUI
```
- Ensure that node_modules are included in /CurriCompassUI, however if it is missing, just run
```shell
npm install
```
- Run the frontend client using:
```shell
ng serve -o
```
- It should by default be running on port 4200

#### Installing Dependencies
- If you require dependencies from third party libraries, you may install it with the following command, and it will automatically be added to package.json and package-lock.json. DO NOT MODIFY THIS DIRECTLY as it will break the functionality of the framework.
```bash
npm install [dependency]
``` 
- to uninstall the dependencies, simply run this command:

```shell
npm uninstall [dependency]
```

### For Backend Developers
#### Setup PHP CLI:
1. Extract php 8.1.0 zip to C:\Program Files
2. Open windows task bar, and search for environment variables.
3. Click on **Environment Variables** button at the very bottom.
4. Locate the variable **PATH**, then double click.
5. Click on **New**, then **Browse**.
6. Locate the directory containing **php-cgi.exe**, then click ok.

#### Setting Up Composer
[![Composer - Installation](https://img.shields.io/badge/Composer-Installation-d7e2f3)](https://getcomposer.org/Composer-Setup.exe)
- Click the badge above to download the executable file of Composer.
- Run the prompt.
- Select the installation for all users.
- Do not tick the developer mode, just proceed.
- locate the php.exe e.g.(C:\XAMPP\php\php.exe), and select.
- skip the proxy url configuration.
- install.

#### PostgreSQL Installation:

[![PostgreSQL - Installation](https://img.shields.io/badge/PostgreSQL-Installation-4d91ff)](https://sbp.enterprisedb.com/getfile.jsp?fileid=1258893)

- Click the badge above to download the setup executable file of PostgreSQL.
- Run the application.
- Leave port, username, and host as default.
- Setup your password.
- install.

#### Configure .env file
- copy and paste .env.example in the same directory where it is located.
- rename the copied file as **.env**.
- configure the key-value pairs to match your postgreSQL configuration (in most cases only the password is required).

#### Run the Backend Client
- cd into CurriCompassAPI
```shell
cd ./CurriCompassAPI
```
- run the initializer CLI command via

```shell
  php artisan app:init-db
```
- given that you already setup the backend, you can freely serve via:
```bash
php artisan serve
```
- it should be running on port 8000 by default.

## Roles

### Backend Development
- John Daniel Tejero

### Frontend Development
- Jason Wayne Hendricks
- Kevin Ray Guevara
- Naif Taratingan

### UI/UX
- Carlos Miguel San Andres

### Database Designer
- John Daniel Tejero
