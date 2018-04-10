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
// Time:     18:59
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher;
use Throwable;


/**
 * Class MiddlewareDispatcherException
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MiddlewareDispatcherException extends \Exception
{
    /**
     * @var MiddlewareDispatcher
     */
    private $middlewareDispatcher;

    /**
     * MiddlewareDispatcherException constructor.
     *
     * @param string $message
     * @param MiddlewareDispatcher $middlewareDispatcher
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, MiddlewareDispatcher $middlewareDispatcher,
        int $code = 0, Throwable $previous = null)
    {
        $this->middlewareDispatcher = $middlewareDispatcher;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return MiddlewareDispatcher
     */
    public function getMiddlewareDispatcher():MiddlewareDispatcher
    {
        return $this->middlewareDispatcher;
    }
}