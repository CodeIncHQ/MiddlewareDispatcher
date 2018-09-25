# PSR-15 middleware dispatcher

`codeinc/middleware-dispatcher` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dispatcher. The middleware dispatcher can be used as a PSR-15 [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface) or as a PSR-15 [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface). It comes in two forms, an abstract class [`AbstractDispatcher`](src/AbstractDispatcher.php) to be extended and a final class [`Dispatcher`](src/Dispatcher.php).

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

// the dispatcher can also be used has a PSR-15 middleware
$psr7Response = $dispatcher->process($psr7ServerRequest, $psr15RequestHandler); 
```

You can use the dispatcher as [an iterator](http://php.net/manual/fr/class.iterator.php) with [`DispatcherIterator`](src/DispatcherIterator.php):
```php
<?php
use CodeInc\MiddlewareDispatcher\Dispatcher;
use CodeInc\MiddlewareDispatcher\DispatcherIterator\DispatcherIterator;

// instantiating the dispatcher
$dispatcher = new Dispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware(),
    new MyThirdMiddleware()
]); 
foreach (new DispatcherIterator($dispatcher) as $middleware) {
    // [...]
} 
``` 


## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/middleware-dispatcher) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/middleware-dispatcher
```


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).