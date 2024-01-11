# Backend Side for CurriCompass

## Directory Structure

```shell
CurriCompassAPI
  |_ app
  |  |_ Console
  |  |_ Exceptions
  |  |_ Http
  |  |  |
  |  |  |
  |  |  |_ Controllers
  |  |  |_ Middleware
  |  |
  |  |_ Models
  |  |_ Providers
  |   
  |_ bootstrap
  |_ config
  |_ database
  |  |
  |  |_ factories
  |  |_ migrations
  |  |_ seeders
  |
  |_ public
  |_ resources
  |  |
  |  |_ css
  |  |_ js
  |  |_ views
  |
  |_ routes
  |_ storage
  |  |
  |  |_ app
  |     |
  |     |_ public
  |  
  |_ tests
  |  |
  |  |_ Feature
  |  |_ Unit
  |  
  |_ vendor
```

## Please Be Guided:
- app - the main directory.
- app/Console - stores developer-defined console commands. 
- app/Exceptions - stores developer-defined exception handlers.
- app/Http - contains important codes for the MVC architecture.
- Http/Controllers - contains developer-defined controllers namely in the method of GET, POST, PUT, PATCH, DELETE, OPTION.
- Http/Middleware - contains developer-defined interceptors before controller is called.
- app/Models - stores class representation of a table.
- app/Providers - stores third-party services that requires provider.
- bootstrap - contains  code for bootstrapping the entire laravel application DO NOT MODIFY THIS PART.
- config - contains all necessary configuration such as environment variable handler, storage allocation, session, cors, websockets, and all.
- database - contains database-related operations
- database/factories - contains code that automatically populates table.
- database/migrations - contains code that updates database table.
- database/seeder - contains the logic for generating records that is used by factory.
- public - contains the bootstrap or entry point of laravel. DO NOT TOUCH THIS PART.
- resources - contains resources such as css, js, and blade template. We do not need this as we will only focus on REST API.
- routes - contains codes that is responsible for defining endpoints. WE WILL ONLY MODIFY THE API PART.
- storage - contains resources that is uploaded by the end-user or the logs done by the server. 
- tests - contains test scripts necessary for the application.
- tests/Feature - contains test for Feature testing.
- tests/Unit - contains tests for Unit testing.
- vendor - contains all the libraries defined in the composer.json file. DO NOT TOUCH THIS PART.

***
.env will contain the environment variables, .env by default will not be present on your code, copy and rename the .env.example to .env, do not touch the .env.example, it serves as a backup.

Feature Testing -  testing of components in the perspective of user as if they are accessing the API, usually a chaining of method, and directly affects database records.
Unit Testing - testing of individual components to ensure that the module is working properly. This is just a mock test, which does not affect the database records.

***
## Nothing follows~
Any custom implementation of command line interface, directive, middleware, etc. will be added here as to how to use.
