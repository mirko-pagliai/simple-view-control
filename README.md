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
- Routing based on Symfony Routing
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

