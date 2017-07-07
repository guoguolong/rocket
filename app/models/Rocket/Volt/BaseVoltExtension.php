<?php
/**
 * Copyright (c) 2017 MirrorOffice.com, All rights reserved.
 * Author: Allen Guo <guojunlong@mirroroffice.com>
 * Create: 2016/06/01
 */
namespace Rocket\Volt;

/**
 * Class BaseVoltExtension
 * @package Rocket\Volt
 */
class BaseVoltExtension
{
    private static $di;

    public function __construct($di)
    {
        self::$di = $di;
    }

    public static function getDI()
    {
        if (!self::$di) {
            $exception = new \Phalcon\Exception();
            throw $exception;
        }
        return self::$di;
    }

    public static function setDI($di)
    {
        self::$di = $di;
    }

    public function getFunctions()
    {
        return $this->registerFunctions();
    }

    public function getFilters()
    {
        return $this->registerFilters();
    }

    protected function registerFunctions()
    {
        return [];
    }

    protected function registerFilters()
    {
        return [];
    }
}
