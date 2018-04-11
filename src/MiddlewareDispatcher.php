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
 * Class MiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareDispatcher implements RequestHandlerInterface, \IteratorAggregate, \Countable
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares = [];

    /**
     * @var int
     */
    private $pointer = 0;

    /**
     * Add a middleware
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        while ($middleware = $this->getNextMiddleware()) {
            return $middleware->process($request, $this);
        }

        // if no middleware generated a response, sending NoResponseAvailable response
        return new NoResponseAvailable();
    }

    /**
     * Returns the next middleware from withing the internal stack.
     *
     * @return null|MiddlewareInterface
     */
    public function getNextMiddleware():?MiddlewareInterface
    {
        if (isset($this->middlewares[$this->pointer])) {
            return $this->middlewares[$this->pointer++];
        }
        return null;
    }

    /**
     * Resets the internal middlewares stack's pointer.
     */
    public function resetMiddlewarePointer():void
    {
        $this->pointer = 0;
    }

    /**
     * @inheritdoc
     * @return \ArrayIterator
     */
    public function getIterator():\ArrayIterator
    {
        return new \ArrayIterator($this->middlewares);
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int
    {
        return count($this->middlewares);
    }
}