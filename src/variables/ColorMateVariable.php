<?php
/**
 * ColorMate plugin for Craft CMS 4.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2022 Værsågod
 */

namespace vaersaagod\colormate\variables;

use Craft;
use vaersaagod\colormate\ColorMate;


class ColorMateVariable
{
    /**
     * Proxy for service methods
     * 
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return ColorMate::$plugin->color->{$name}(...$arguments);
    }
}
