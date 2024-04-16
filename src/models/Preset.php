<?php
/**
 * ColorMate plugin for Craft CMS 4.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2022 Værsågod
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
     * @deprecated Since 2.3.0
     */
    public string $viewMode = ColorMateField::FIELD_VIEW_MODE_COMPACT;

    /**
     * @var boolean
     */
    public bool $showClear = true;

    /**
     * @var boolean
     */
    public bool $showCustom = false;

    /**
     * @var boolean
     */
    public bool $showOpacity = false;

    /**
     * @var boolean
     */
    public bool $showTooltip = true;

    /**
     * @var array
     */
    public array $colors = [];
    
    /**
     * @var string|null
     */
    public ?string $default = null;

    /** @inheritdoc */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['viewMode'])) {
            Craft::$app->getDeprecator()->log(__METHOD__, "The `viewMode` preset config setting is deprecated. You should remove references to it from your `colormate.php` config file.");
        }
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
     *
     * @return PresetColor|null
     */
    public function getColorByHandle(string $handle): ?PresetColor
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
