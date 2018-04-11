# PSR-15 middleware dispatcher

`codeinc/middleware-dispatcher` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware dispatcher. The middleware dispatcher behaves as a PSR-15 [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface).

## Usage

```php
<?php
use CodeInc\MiddlewareDispatcher\MiddlewareDispatcher;

$middlewareDispatcher = new MiddlewareDispatcher();

// adding the middleware 
$middlewareDispatcher->addMiddleware(new MyFirstMiddleware);
$middlewareDispatcher->addMiddleware(new MySecondMiddleware);
$middlewareDispatcher->addMiddleware(new MyThirdMiddleware);

// handling the request
$psr7Response = $middlewareDispatcher->handle($psr7ServerRequest);
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/middleware-dispatcher) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/middleware-dispatcher
```


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).