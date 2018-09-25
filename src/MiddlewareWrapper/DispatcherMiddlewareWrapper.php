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


/**
 * Class DispatcherMiddlewareWrapper
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DispatcherMiddlewareWrapper extends AbstractDispatcherMiddlewareWrapper
{
    /**
     * @var AbstractDispatcher
     */
    private $dispatcher;

    /**
     * DispatcherMiddlewareWrapper constructor.
     *
     * @param AbstractDispatcher $dispatcher
     */
    public function __construct(AbstractDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     * @return AbstractDispatcher
     */
    protected function getDispatcher():AbstractDispatcher
    {
        return $this->dispatcher;
    }
}