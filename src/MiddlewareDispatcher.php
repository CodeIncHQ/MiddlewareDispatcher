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
use SebastianBergmann\CodeCoverage\Node\Iterator;


/**
 * Class MiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareDispatcher implements MiddlewareDispatcherInterface, \IteratorAggregate
{
    /**
     * @var iterable
     */
    private $middleware;

    /**
     * @var ResponseInterface|null
     * @see MiddlewareDispatcher::getDefaultResponse()
     */
    private $defaultResponse;

    /**
     * MiddlewareDispatcher constructor.
     *
     * @param iterable|null $middleware
     * @param null $defaultResponse
     */
    public function __construct(iterable $middleware, ?$defaultResponse = null)
    {
        $this->setMiddleware($middleware);
        if ($defaultResponse !== null) {
            $this->setDefaultResponse($defaultResponse);
        }
    }

    /**
     * @param ResponseInterface $defaultResponse
     */
    public function setDefaultResponse(ResponseInterface $defaultResponse):void
    {
        $this->defaultResponse = $defaultResponse;
    }

    /**
     * @param iterable $middleware
     */
    public function setMiddleware(iterable $middleware):void
    {
        $this->middleware = $middleware;
    }

    /**
     * @return Iterator
     */
    public function getMiddleware():iterable
    {
        return $this->middleware;
    }

    /**
     * @return \Generator
     */
    public function getIterator():\Iterator
    {
        yield from $this->middleware;
    }

    /**
     * @return ResponseInterface
     */
    public function getDefaultResponse():ResponseInterface
    {
        return $this->defaultResponse ?? new NoResponseAvailable();
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws MiddlewareDispatcherException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        while ($this->getIterator()->valid()) {
            $middleware = $this->getIterator()->current();
            if (!$middleware instanceof MiddlewareInterface) {
                throw MiddlewareDispatcherException::notAMiddleware($middleware);
            }
            $this->getIterator()->next();
            return $middleware->process($request, $this);
        }

        // if no middleware generated a response, sending NoResponseAvailable response
        return $this->getDefaultResponse();
    }
}