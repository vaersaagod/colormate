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
use vaersaagod\colormate\fields\ColorMateField;

/**
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 */
class Preset extends Model
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
    public $viewMode = ColorMateField::FIELD_VIEW_MODE_COMPACT;

    /**
     * @var boolean
     */
    public $showClear = true;

    /**
     * @var boolean
     */
    public $showCustom = false;

    /**
     * @var boolean
     */
    public $showOpacity = false;

    /**
     * @var array
     */
    public $colors = [];
    
    /**
     * @var string|null
     */
    public $default = null;

    

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return PresetColor[]
     */
    public function getColors(): array
    {
        $r = [];

        foreach ($this->colors as $handle => $color) {
            $r[] = new PresetColor(array_merge(
                $color,
                ['handle' => $handle]
            ));
        }

        return $r;
    }
    
    /**
     * @param string $handle
     * @return PresetColor|null
     */
    public function getColorByHandle($handle)
    {
        if (!isset($this->colors[$handle])) {
            return null;
        }

        return new PresetColor(array_merge(
            $this->colors[$handle],
            ['handle' => $handle]
        ));
    }    
}
