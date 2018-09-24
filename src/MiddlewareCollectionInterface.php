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
use CodeInc\CollectionInterface\CollectionInterface;
use Psr\Http\Server\MiddlewareInterface;


/**
 * Interface MiddlewareCollectionInterface
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface MiddlewareCollectionInterface extends CollectionInterface
{
    /**
     * @inheritdoc
     * @return MiddlewareInterface
     */
    public function current():MiddlewareInterface;

    /**
     * @inheritdoc
     */
    public function rewind():void;

    /**
     * @inheritdoc
     */
    public function next():void;

    /**
     * @inheritdoc
     * @return bool
     */
    public function valid():bool;

    /**
     * @inheritdoc
     * @return int
     */
    public function key():int;

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int;
}