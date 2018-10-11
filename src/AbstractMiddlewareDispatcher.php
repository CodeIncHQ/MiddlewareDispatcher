<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     10/04/2018
// Time:     18:49
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractMiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/MiddlewareDispatcher/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/MiddlewareDispatcher
 */
abstract class AbstractMiddlewareDispatcher implements RequestHandlerInterface
{
    /**
     * @var \Iterator|null
     */
    private $middlewareIterator;

    /**
     * Returns the middleware.
     *
     * @return iterable|MiddlewareInterface[]
     */
    abstract public function getMiddleware():iterable;

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws MiddlewareDispatcherException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        if (!$this->middlewareIterator) {
            $middleware = $this->getMiddleware();
            if ($middleware instanceof \Iterator) {
                $this->middlewareIterator = $middleware;
            }
            elseif ($middleware instanceof \IteratorAggregate) {
                $this->middlewareIterator = $middleware->getIterator();
            }
            else {
                $this->middlewareIterator = new \ArrayIterator($middleware);
            }
            $this->middlewareIterator->rewind();
        }
        while ($this->middlewareIterator->valid()) {
            $middleware = $this->middlewareIterator->current();
            if (!$middleware instanceof MiddlewareInterface) {
                throw MiddlewareDispatcherException::notAMiddleware($middleware);
            }
            $this->middlewareIterator->next();
            return $middleware->process($request, $this);
        }
        $this->middlewareIterator = null;
        return $this->getFinalRequestHandler()->handle($request);
    }

    /**
     * Returns the final PSR-15 request handler called if no middleware can process the request.
     *
     * @return RequestHandlerInterface
     */
    protected function getFinalRequestHandler():RequestHandlerInterface
    {
        return new DefaultFinalRequestHandler();
    }
}