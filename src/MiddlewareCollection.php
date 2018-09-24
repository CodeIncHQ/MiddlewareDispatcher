<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     24/09/2018
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher;
use Psr\Http\Server\MiddlewareInterface;


/**
 * Class MiddlewareCollection
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareCollection implements MiddlewareCollectionInterface
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middleware = [];

    /**
     * @var int|null
     */
    private $iteratorPosition;

    /**
     * MiddlewareCollection constructor.
     *
     * @param iterable|null $middleware
     * @throws MiddlewareDispatcherException
     */
    public function __construct(?iterable $middleware = null)
    {
        if ($middleware !== null) {
            $this->addMiddlewares($middleware);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @param iterable $middleware
     * @throws MiddlewareDispatcherException
     */
    public function addMiddlewares(iterable $middleware):void
    {
        foreach ($middleware as $item) {
            if (!$item instanceof MiddlewareInterface) {
                throw MiddlewareDispatcherException::notAMiddleware($item);
            }
            $this->addMiddleware($item);
        }
    }

    /**
     * @inheritdoc
     * @return MiddlewareInterface
     */
    public function current():MiddlewareInterface
    {
        return $this->middleware[$this->iteratorPosition];
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function key():int
    {
        return $this->iteratorPosition;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function valid():bool
    {
        return array_key_exists($this->iteratorPosition, $this->middleware);
    }

    /**
     * @inheritdoc
     */
    public function next():void
    {
        $this->iteratorPosition++;
    }

    /**
     * @inheritdoc
     */
    public function rewind():void
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int
    {
        return count($this->middleware);
    }
}