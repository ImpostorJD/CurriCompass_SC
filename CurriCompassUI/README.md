# CurriCompassUI

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 17.0.10.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The application will automatically reload if you change any of the source files.

## Code scaffolding

Run `ng generate component component-name` to generate a new component. You can also use `ng generate directive|pipe|service|class|guard|interface|enum|module`.

## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory.

## Running unit tests

Run `ng test` to execute the unit tests via [Karma](https://karma-runner.github.io).

## Running end-to-end tests

Run `ng e2e` to execute the end-to-end tests via a platform of your choice. To use this command, you need to first add a package that implements end-to-end testing capabilities.

## Further help

To get more help on the Angular CLI use `ng help` or go check out the [Angular CLI Overview and Command Reference](https://angular.io/cli) page.


## Directory Structure
```bash
  src
  |_ app
  |_ assets
```

app - holds most of the component codes, routing, and services.
assets - holds the assets such as image, video, etc.

***
## Please be guided:
When you want to apply CSS globally, add the class via **styles.css**
DO NOT TOUCH main.ts, main.server.ts

app.config.ts - handles most of the dependency injections, you do not need to touch this when you generate component, services, directives, etc. via the CLI.

app.routes.ts - handles the routing configurations. Only include components that requires routing, otherwise if the component merely serves as an abstraction of an element such as registration form, button, do not include.

***
## When adding a new route:
open app.routes.ts
```ts
import { Routes } from '@angular/router';
import { YourComponent } from './yourcomponent/yourcomponent.component';

export const routes: Routes = [
  {
    path: "your_path", //do not include the /, use the absolute path
    component: YourComponent, //import the component and pass the Class, do not instantiate.
  }

];
```
for more information, please read [Routing Documentations.]("https://angular.io/guide/routing-overview")

***
## NOTHING FOLLOWS:

More will be added when necessary.

