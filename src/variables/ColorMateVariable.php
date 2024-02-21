<?php
/**
 * ColorMate plugin for Craft CMS 5.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
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
