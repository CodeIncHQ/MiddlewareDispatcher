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
abstract class AbstractDispatcher implements RequestHandlerInterface
{
    /**
     * Returns the middleware.
     *
     * @return iterable|MiddlewareInterface[]
     */
    abstract public function getMiddleware():iterable;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|ResponseInterface
     * @throws DispatcherException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        while ($this->getIterator()->valid()) {
            $middleware = $this->getIterator()->current();
            if (!$middleware instanceof MiddlewareInterface) {
                throw DispatcherException::notAMiddleware($middleware);
            }
            $this->getIterator()->next();
            return $middleware->process($request, $this);
        }

        // if no middleware generated a response, sending NoResponseAvailable response
        return new NoResponseAvailable();
    }

    /**
     * Alias of getMiddleware().
     *
     * @uses AbstractDispatcher::getMiddleware()
     * @return \Generator
     */
    private function getIterator():\Generator
    {
        yield from $this->getMiddleware();
    }
}