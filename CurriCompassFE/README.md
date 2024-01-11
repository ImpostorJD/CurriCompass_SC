# Frontend Side for CurriCompass

## Directory Structure

```shell
CurriCompassFE
  |_ public
  |  |_ static
  |  |_ environment
  |
  |_ src
     |_ utilities
        |_ routing
     |_ assets
     |_ components
     |_ hooks
     |_ views
     |_ test
```
Please be guided:
- public/static - contains static files such as documentation for testing
- public/environment - contains files that defines the environment variables

- src/utilities - contains files that is shared across the application such as HTTP handlers, routing handlers, and other developer-defined utility code.
- src/utilities/routing - contains files that defines the routing logic.
- src/assets - contains static images such as logo, hero banner, and other assets that are shared across the application.
- src/components - contains javascript code that contains shared components such as custom form component, custom radio button, and other.
- src/hooks - contains javascript code that contains developer-defined hooks.
- src/views - contains javascript code that contains the pages for example: registration page etc.
- src/tests - contains javascript code that contains test scripts.

***
When writing a component and view always capitalize the first letter. Ensure that the name used is descriptive. 
```js
 function Component(params) {
    return (
        <View/>
    )
 }
```
When writing a utility code, ensure that the code is self-explanatory, comments will only be included if there is an ambiguity in the code. This also helps with readability, wherein the code is easily understood when the developer highlights the imported code.

```js
/**
 * Just a brief description of the function or class.
 *
 * @param {Object} routes - These are arrays of views
 **/
function routingUtil(...routes) {
    //code here
}
```
**NOTE:** You can add your custom directories whenever necessary, however the directory structure created will serve as a separation of concerns for ease of access and organization of the overall source code.
