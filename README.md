# simple-view-controller
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![CI](https://github.com/mirko-pagliai/simple-view-controller/actions/workflows/ci.yml/badge.svg)](https://github.com/mirko-pagliai/simple-view-controller/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/mirko-pagliai/simple-view-controller/graph/badge.svg?token=0mNM4qL98u)](https://codecov.io/gh/mirko-pagliai/simple-view-controller)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/simple-view-controller/badge)](https://www.codefactor.io/repository/github/mirko-pagliai/simple-view-controller)

`simple-view-controller` is a lightweight PHP framework focused exclusively on the **Controller** and **View** layers.

It is designed as a library, not as a full-stack framework, and intentionally omits models, ORMs, template engines, and dependency injection containers.

The goal is to provide a minimal, explicit, and predictable runtime for small web applications.

## Design goals

- Explicit behavior over implicit conventions
- Minimal and stable public API
- No magic exposed to the user
- Framework as a library, not as an application
- Use Symfony components without adopting a full-stack approach

## What the framework provides

- An `Application` runtime to handle HTTP requests
- Routing based on [Symfony Routing](https://symfony.com/doc/current/create_framework/routing.html)
- A base `Controller` class
- A minimal `View` abstraction
- Automatic injection of the current `Request` into the `View`
- Automatic template resolution and rendering
- Centralized error handling (404 and 500)
- PSR-3 compatible logging
- Environment variable loading via dotenv

## What the framework does NOT provide

By design, the framework does not include:

- Models or a data layer
- ORMs or database abstractions
- A dependency injection container
- A templating engine
- HTML helpers
- CLI tooling
- Code generation
- An application entry point (`index.php`)
- A global debug switch

All of these are application-level concerns.

## Routing

Routing is based on [Symfony Routing](https://symfony.com/doc/current/create_framework/routing.html).

Routes can be defined in two supported ways.

### Option 1: defining routes in `config/routes.php` (best way)

By default, the application will try to load routes from `config/routes.php`

The file must return a `RouteCollection`:

```php
<?php
declare(strict_types=1);

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use App\Controller\HomeController;

$routes = new RouteCollection();
$routes->add('home', new Route(path: '/', defaults: [
    '_controller' => ['\App\Controller\HomeController', 'index'],
]));

return $routes;
```

### Option 2: passing routes directly to the `Application`

Alternatively, you can pass a `RouteCollection` instance as an application argument:
```php
<?php
declare(strict_types=1);

use SimpleVC\Application;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('home', new Route(path: '/', defaults: [
    '_controller' => ['\App\Controller\HomeController', 'index'],
]));

$app = new Application($routes);

$response = $app->handle();
$response->send();
```

## Controllers

Controllers must extend `SimpleVC\Controller`:
```php
<?php
declare(strict_types=1);

namespace App\Controller;

use SimpleVC\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        //Here's something...
    }
}
```

The runtime invokes controller methods.
What they return is interpreted by the runtime, which is responsible for producing the final `Response`.

The framework explicitly supports multiple controller styles.

### Example 1: controller preparing the `View` (implicit rendering)

```php
public function index(): void
{
    $this->set('title', 'Home');
    $this->set('message', 'Hello world');
}
```
In this case:

- the controller does not return anything
- the controller does not implicity call `render()`
- the runtime resolves the template automatically
- the runtime renders the template and produces the `Response`

The runtime will automatically resolve the template as `templates/{ControllerName}/{methodName}.php`.  
So in this example case it would be `templates/Home/index.php`.  
Inside this template file there will be the variables `$title` and `$text`.

### Example 2: controller explicitly calls the `render()` method (returns a `Response`)

```php
public function index(): Response
{
    $this->set('title', 'Home');
    $this->set('message', 'Hello world');
    
    return $this->render('Custom/stuff.php');
}
```
The result is like the previous case, but in this one it will use the `templates/Custom/stuff.php` file.

### Example 3: controller directly returns a `Response`

```php
public function index(): Response
{
    return new Response('<strong>Hello world</strong>');
}
```

In this case, the returned `Response` is used directly by the runtime.

## Views

The `View` abstraction is intentionally minimal.

- Variables are assigned by the controller  
- The current `Request` is injected automatically
- By default, the template is resolved and rendered by the runtime

Templates are plain PHP files.
