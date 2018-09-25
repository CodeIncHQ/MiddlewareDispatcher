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
// Date:     25/09/2018
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher\MiddlewareWrapper;
use CodeInc\MiddlewareDispatcher\AbstractDispatcher;
use CodeInc\MiddlewareDispatcher\DispatcherException;
use CodeInc\MiddlewareDispatcher\NoResponseAvailable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractDispatcherMiddlewareWrapper
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractDispatcherMiddlewareWrapper implements MiddlewareInterface
{
    /**
     * @return AbstractDispatcher
     */
    abstract protected function getDispatcher():AbstractDispatcher;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws DispatcherException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $resp = $this->getDispatcher()->handle($request);
        if (!$resp instanceof NoResponseAvailable) {
            return $resp;
        }
        else {
            return $handler->handle($request);
        }
    }
}