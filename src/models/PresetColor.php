<?php
/**
 * ColorMate plugin for Craft CMS 3.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2020 Værsågod
 */

namespace vaersaagod\colormate\models;

use Craft;
use craft\base\Model;

/**
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 */
class PresetColor extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $handle = '';

    /**
     * @var string
     */
    public $color = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    public function init()
    {
        
    }

}
