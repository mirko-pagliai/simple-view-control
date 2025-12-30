# simple-view-controller
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![CI](https://github.com/mirko-pagliai/simple-view-controller/actions/workflows/ci.yml/badge.svg)](https://github.com/mirko-pagliai/simple-view-controller/actions/workflows/ci.yml)

A lightweight PHP framework focused on View and Controller layers - the "VC" without the "M". 

It provides clean, minimal implementation for handling HTTP requests, routing, and view rendering, without database/ORM complexity or full-stack framework overhead.

## Key Features

- **Clean Controller abstraction** with automatic View injection
- **Flexible View system** with layout support and auto-template detection
- **Symfony Routing integration** for robust route management
- **Request/Response handling** via Symfony HttpFoundation
- **Comprehensive error handling** with custom error pages and DEBUG mode
- **Testing utilities** with ControllerTestCase for easy controller testing
- **Route parameter support** with automatic placeholder substitution
- **PSR-4 autoloading** compatible
- **Zero configuration** for basic usage
