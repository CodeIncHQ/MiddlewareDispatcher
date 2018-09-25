# PSR-15 middleware dispatcher

`codeinc/middleware-dispatcher` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dispatcher. The middleware dispatcher can behave as a PSR-15 [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface). It comes in two forms, an abstract class [`AbstractDispatcher`](src/AbstractDispatcher.php) to be extended and a final class [`Dispatcher`](src/Dispatcher.php).

The class [`DispatcherMiddlewareWrapper`](src/DispatcherMiddlewareWrapper.php) alllowes to use the dispatcher as a PSR-15 [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface).

If the dispatcher is used as a request handler and if it can't handle the request, a [`NoResponseAvailable`](src/NoResponseAvailable.php) PSR-7 response is returned.

## Usage

Usage of the dispatcher behaving as a PSR-15 request handler:
```php
<?php
use CodeInc\MiddlewareDispatcher\Dispatcher;

// instantiating the dispatcher
$dispatcher = new Dispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware()
]);
$dispatcher->addMiddleware(new MyThirdMiddleware());

// handling the request 
// will return a NoResponseAvailable object if the request can not be processed by the middleware
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
$psr7Response = $dispatcher->handle($psr7ServerRequest); 
```

The dispatcher can behace as a PSR-15 [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface) using [`DispatcherMiddlewareWrapper`](src/DispatcherMiddlewareWrapper.php):
```php
<?php
use CodeInc\MiddlewareDispatcher\Dispatcher;
use CodeInc\MiddlewareDispatcher\DispatcherMiddlewareWrapper;

// instantiating the dispatcher
$dispatcher = new Dispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware(),
    new MyThirdMiddleware()
]); 

// handling the request 
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
// --> $psr15RequestHandler must be an object implementing RequestHandlerInterface
$psr7Response = (new DispatcherMiddlewareWrapper($dispatcher))->process($psr7ServerRequest, $psr15RequestHandler); 
``` 

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/middleware-dispatcher) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/middleware-dispatcher
```


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).