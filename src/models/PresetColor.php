<?php
/**
 * ColorMate plugin for Craft CMS 5.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
 */

namespace vaersaagod\colormate\models;

use craft\base\Model;

/**
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 */
class PresetColor extends Model
{
    /**
     * @var string
     */
    public string $name = '';

    /**
     * @var string
     */
    public string $handle = '';

    /**
     * @var string
     */
    public string $color = '';


}
