<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/30
 */

namespace Viliy\SMS\Support;

/**
 * Trait Render
 * @package Viliy\SMS\Support
 */
trait Render
{
    protected function render($content, array $variables = [])
    {
        $search = $replace = [];
        foreach ($variables as $key => $value) {
            $search[] = "{{$key}}";
            $replace[] = $value;
        }

        return str_replace($search, $replace, $content);
    }
}
