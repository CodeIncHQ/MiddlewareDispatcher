# PSR-15 middleware dispatcher

`codeinc/middleware-dispatcher` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dispatcher. The middleware dispatcher can behave as a PSR-15 [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface) or a PSR-15 [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface).

If the dispatcher is used as a request handler and if non of it's middleware can handle the request, a [`NoResponseAvailable`](src/NoResponseAvailable.php) PSR-7 response is returned 

## Usage

Usage of the dispatcher behaving as a PSR-15 request handler:
```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareDispatcher;

// instantiating the dispatcher
$middlewareDispatcher = new MiddlewareDispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware(),
    new MyThirdMiddleware()
]);

// handling the request 
// will return a NoResponseAvailable object if the request can not be processed by the middleware
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
$psr7Response = $middlewareDispatcher->handle($psr7ServerRequest); 
```

Usage of the dispatcher behaving as a PSR-15 middlware:
```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareDispatcher;

// instantiating the dispatcher
$middlewareDispatcher = new MiddlewareDispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware(),
    new MyThirdMiddleware()
]); 

// handling the request 
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
// --> $psr15RequestHandler must be an object implementing RequestHandlerInterface
$psr7Response = $middlewareDispatcher->process($psr7ServerRequest, $psr15RequestHandler); 
``` 


Optionnaly you can use [`MiddlewareCollection`](src/MiddlewareCollection.php) in order to add extra middleware objects after instantiating the dispatcher:
```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareDispatcher;
use CodeInc\MiddlewareDispatcher\MiddlewareCollection;
use GuzzleHttp\Psr7\ServerRequest;

// creating the collection
$middlewareCollection = new MiddlewareCollection([
    new MyFirstMiddleware(),
    new MySecondMiddleware(),
]);

// instantiating the dispatcher
$middlewareDispatcher = new MiddlewareDispatcher($middlewareCollection);

// adding an extra middleware
// --> will be process even if added after the dispatcher instantiation
$middlewareCollection->addMiddleware(new MyThirdMiddleware()); 

// handling the request 
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
$psr7Response = $middlewareDispatcher->handle($psr7ServerRequest); 
``` 

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/middleware-dispatcher) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/middleware-dispatcher
```


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).