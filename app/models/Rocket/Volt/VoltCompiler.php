<?php
/**
 * Copyright (c) 2017 MirrorOffice.com, All rights reserved.
 * Author: Allen Guo <guojunlong@mirroroffice.com>
 * Create: 2016/06/01
 */
namespace Rocket\Volt;

use Phalcon\Mvc\View\Engine\Volt\Compiler;

/**
 * Class VoltCompiler
 * @package Rocket\Volt
 */
class VoltCompiler extends Compiler
{
    /**
     * @param string $path
     * @param string $compiledPath
     * @param null   $extendsMode
     * @return array|string
     */
    public function compileFile($path, $compiledPath, $extendsMode = null)
    {
        $skinPath = $this->getOption('layoutDir');
        if ($skinPath) {
            $skinTemplate = str_replace(
                $this->getDI()->getView()->getViewsDir(),
                $skinPath,
                $path
            );

            if (is_readable($skinTemplate)) {
                $path = $skinTemplate;
            }
        }
        return parent::compileFile($path, $compiledPath, $extendsMode);
    }
}
