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
use Psr\Http\Server\MiddlewareInterface;


/**
 * Class MiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareDispatcher extends AbstractMiddlewareDispatcher
{
    /**
     * @var iterable
     */
    private $middleware;

    /**
     * MiddlewareDispatcher constructor.
     *
     * @param iterable|null $middleware
     * @throws MiddlewareDispatcherException
     */
    public function __construct(?iterable $middleware = null)
    {
        if ($middleware !== null) {
            foreach ($middleware as $item) {
                if (!$item instanceof MiddlewareInterface) {
                    throw MiddlewareDispatcherException::notAMiddleware($item);
                }
                $this->addMiddleware($item);
            }
        }
    }

    /**
     * Adds a middleware to the dispatcher.
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @inheritdoc
     * @return iterable
     */
    public function getMiddleware():\Iterator
    {
        return new \ArrayIterator($this->middleware);
    }
}