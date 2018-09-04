<?php
/**
 * Copyright (c) 2017 MirrorOffice.com, All rights reserved.
 * Author: Allen Guo <guojunlong@mirroroffice.com>
 * Create: 2016/06/01
 */
namespace Rocket\Volt;

class VoltAdapter
{
    /**
     * @param Rocket\Volt\VoltEngine $volt
     */
    public function register(VoltEngine $volt, BaseVoltExtension $ext)
    {
        $compiler = $volt->getCompiler();
        $filters = $ext->getFilters();
        $functions = $ext->getFunctions();
        foreach ($filters as $filterName => $filter) {
            $compiler->addFilter($filterName, function ($resolvedArgs, $exprArgs) use ($ext, $filter) {
                $class_name = get_class($ext);
                $cmd = "{$class_name}::{$filter}({$resolvedArgs});";
                return $cmd;
            });
        }
        foreach ($functions as $functionName => $function) {
            $compiler->addFunction($functionName, function ($resolvedArgs, $exprArgs) use ($ext, $function) {
                $class_name = get_class($ext);
                return "{$class_name}::{$function}({$resolvedArgs});";
            });
        }
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function dump($str)
    {
        return $str;
    }
}
