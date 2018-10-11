# PSR-15 middleware dispatcher

`codeinc/middleware-dispatcher` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dispatcher. The middleware dispatcher behaves as a PSR-15 [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface). It comes in two forms, an abstract class [`AbstractMiddlewareDispatcher`](src/AbstractMiddlewareDispatcher.php) to be extended and a final class [`MiddlewareDispatcher`](src/MiddlewareDispatcher.php).

If none of middleware added to the dispatcher can process the request, a final request handler is called. By default this request handler is [`DefaultFinalRequestHandler`](src/DefaultFinalRequestHandler.php) which returns a `NotFoundResponse`.

## Usage

```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareDispatcher;

// instantiating the dispatcher
$dispatcher = new MiddlewareDispatcher([
    new MyFirstMiddleware(),
    new MySecondMiddleware()
]);
$dispatcher->addMiddleware(new MyThirdMiddleware());

// handling the request 
// will return a NoResponseAvailable object if the request can not be processed by the middleware
// --> $psr7ServerRequest must be an object implementing ServerRequestInterface
$psr7Response = $dispatcher->handle($psr7ServerRequest); 
```

An alternative dispatcher called [`MiddlewareIteratorDispatcher`](src/MiddlewareIteratorDispatcher.php) allows to use an iterator as source for the dispatcher. Below is an example using a [generator](http://php.net/manual/en/language.generators.overview.php). In this example, the middleware objects are instantiated on the fly. This avoids instantiating unsued middleware objects. If the first middleware is capable of generating a valid response, the next ones will never be instantiated.

```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareIteratorDispatcher;

$dispatcher = new MiddlewareIteratorDispatcher(function():Generator {
    yield new MyFirstMiddleware();
    yield new MySecondMiddleware();
    yield new MyThirdMiddleware();
});
$psr7Response = $dispatcher->handle($psr7ServerRequest); 
```


## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/middleware-dispatcher) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/middleware-dispatcher
```


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).