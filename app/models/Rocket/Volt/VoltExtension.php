<?php
/**
 * Copyright (c) 2017 MirrorOffice.com, All rights reserved.
 * Author: Allen Guo <guojunlong@mirroroffice.com>
 * Create: 2016/06/01
 */
namespace Rocket\Volt;

use Phalcon\Mvc\View\Engine\Volt;

/**
 * Class VoltExtension
 * @package Rocket\Volt
 */
class VoltExtension extends Volt
{
    // Override default Volt getCompiler method
    public function getCompiler()
    {
        if (!$this->_compiler) {
            $this->_compiler = new VoltCompilerExtension($this->getView());
            $this->_compiler->setOptions($this->getOptions());
            $this->_compiler->setDI($this->getDI());
        }
        return $this->_compiler;
    }
}
