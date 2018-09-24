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


/**
 * Class MiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    /**
     * @var MiddlewareCollection
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
     * @param MiddlewareCollection|null $middlewareCollection
     * @param null $defaultResponse
     * @throws MiddlewareDispatcherException
     */
    public function __construct(?MiddlewareCollection $middlewareCollection = null, ?$defaultResponse = null)
    {
        $this->middleware = $middlewareCollection ?? new MiddlewareCollection();
        $this->defaultResponse = $defaultResponse;
    }

    /**
     * @return MiddlewareCollectionInterface
     */
    public function getMiddleware():MiddlewareCollectionInterface
    {
        return $this->middleware;
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
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        while ($this->getMiddleware()->valid()) {
            $middleware = $this->getMiddleware()->current();
            $this->getMiddleware()->next();
            return $middleware->process($request, $this);
        }

        // if no middleware generated a response, sending NoResponseAvailable response
        return $this->getDefaultResponse();
    }
}