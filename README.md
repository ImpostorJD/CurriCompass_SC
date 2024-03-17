![Hero](https://www.helpforassessment.com/blog/wp-content/uploads/2020/08/how-to-do-a-capstone-project.jpg)

[![Progress - In Development](https://img.shields.io/badge/Progress-In_Development-D30000?style=for-the-badge)]()

***
# CurriCompass

## Overview
This project entitled CurriCompass is a Capstone project in compliance with the requirements for Capstone Project (CAP1). The goal of this project is to streamline the pre-advisement process of students for the upcoming semestral enrollment. 

## PLEASE READ THE INSTRUCTIONS BEFORE PROCEEDING!
[![UI Instructions](https://img.shields.io/badge/UI-Instructions-ffffff)](https://github.com/JohnDanielTejero/CurriCompass_SC/blob/main/CurriCompassUI/README.md)
[![API Instructions](https://img.shields.io/badge/API-Instructions-ff3421)](https://github.com/JohnDanielTejero/CurriCompass_SC/tree/main/CurriCompassAPI)

## Features
- Algorithm-based decision-making for academic advising
- Adjustable lists of courses 
- Powerful tool for defining curriculum

## Progress:
### Finish Module:
- User Module
- Curriculum Module
- Courses Module
- Program Module
- Student Record Management Module

### Ongoing Module:
- School Calendar Module
- Consultation Module
- Profile Module
- Authentication Module
  
## Technologies Used
- Angular 17.0.2
- TailwindCSS
- Laravel v 10.3.0
- php v 8.1.0
- Visual Studio Code
- GitHub
- PenPot Desktop

## Installation
NOTE: Setup both Frontend and Backend to run the Application in Development Environment

### For Frontend Developers

[![NodeJS - Installation](https://img.shields.io/badge/NodeJS-Installation-2ea44f)](https://nodejs.org/dist/v20.10.0/node-v20.10.0-x64.msi)
1. Click the badge above to download the executable file of NodeJS
2. Run the prompt.
3. Just install the node runtime.
4. Open windows task bar and search for environment variables.
5. Locate PATH, double click.
6. Locate nodeJS in the PATH, if not found, proceed to the next step.
7. Click on Add, then Browse, then look for the file directory of nodeJS.
8. Close the environment variable.

#### Run Frontend Client
##### Configure config file
- Copy and paste Config copy.ts in the same directory where it is located.
- Rename the copied file as **Config.ts**.
- In the file, you will see an exported constant **environment**.

```ts
export const environment = {
  environment: EnvironmentType.DEV,
  apiUrl: apiUrls[EnvironmentType.DEV],
};
```
- set both the environment and apiUrl to .DEV and save.
---

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
#### Setting up WAMP
[![WAMP - Installation](https://img.shields.io/badge/WAMP-Installation-9966cc)](https://sourceforge.net/projects/wampserver/files/WampServer%203/WampServer%203.0.0/wampserver3.3.2_x64.exe/download)
1. Run the installer.
2. Open windows task bar, and search for environment variables.
3. Click on **Environment Variables** button at the very bottom.
4. Locate the variable **PATH**, then double click.
5. Click on **New**, then **Browse**.
6. Locate the directory containing **php-cgi.exe** inside wamp/bin/php/php8.1.0/, then click ok.
7. Open php.ini located in the same directory.
8. Search (ctrl + f) for extension.
9. Create new line and add this: extension=php_pdo_pgsql.dll
10. Save changes.
11. Restart WAMP server

#### Setting Up Composer
[![Composer - Installation](https://img.shields.io/badge/Composer-Installation-d7e2f3)](https://getcomposer.org/Composer-Setup.exe)
- Click the badge above to download the executable file of Composer.
- Run the prompt.
- Select the installation for all users.
- Do not tick the developer mode, just proceed.
- Locate the php.exe e.g.(C:\XAMPP\php\php.exe), and select.
- Skip the proxy url configuration.
- Install.

#### PostgreSQL Installation:

[![PostgreSQL - Installation](https://img.shields.io/badge/PostgreSQL-Installation-4d91ff)](https://sbp.enterprisedb.com/getfile.jsp?fileid=1258893)

- Click the badge above to download the setup executable file of PostgreSQL.
- Run the application.
- Leave port, username, and host as default.
- Setup your password.
- Install.

#### Configure .env file
- Copy and paste .env.example in the same directory where it is located.
- Rename the copied file as **.env**.
- Configure the key-value pairs to match your postgreSQL configuration (in most cases only the password is required).

#### Run the Backend Client
- cd into CurriCompassAPI
```shell
cd ./CurriCompassAPI
```
- Run the initializer CLI command via

```shell
  php artisan app:init-db
```
- given that you already setup the backend, you can freely serve via:
```bash
php artisan serve
```
- It should be running on port 8000 by default.
