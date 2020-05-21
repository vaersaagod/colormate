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
use craft\validators\ColorValidator;
use vaersaagod\colormate\ColorMate;

/**
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 *
 * @property null|string $color
 */
class Color extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $handle = '';

    /**
     * @var string
     */
    public $custom = '';

    /**
     * @var int
     */
    public $opacity = 100;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string|null
     */
    public $baseColor = null;

    /**
     * @var null|Preset
     */
    public $preset = null;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['handle', 'string'],
            ['custom', ColorValidator::class, 'when' => function () { return $this->custom !== ''; }, 'message' => Craft::t('colormate', 'Custom color is not a valid hex value')],
            ['opacity', 'required'],
            ['opacity', 'default', 'value' => 100],
            ['opacity', 'integer', 'min' => 0, 'max' => 100]
        ];
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getColor() ?? '';
    }

    /**
     * @param string $format
     * @return string|null
     */
    public function getColor($format = 'rgb')
    {
        if ($this->baseColor === null) {
            return null;
        }
        
        $color = ColorMate::$plugin->color->getColor($this->baseColor);
        
        if ($this->opacity !== 100) {
            $opacityAdjust = $this->opacity/100;
            $color = ColorMate::$plugin->color->adjustColor($color, ['alpha' => ($color->getRgb()->getAlpha() * $opacityAdjust) * -1]);
        }
        
        if ($format==='hex') {
            return ColorMate::$plugin->color->rgb2hex($color);
        }
        
        if ($format === 'hsl') {
            return $color->getHsl();
        }
        
        return $color->getRgb();
    }

    /**
     * @return bool
     */
    public function isCustom(): bool
    {
        return $this->custom !== '';
    }

    /**
     * @return bool
     */
    public function hasTransparency(): bool
    {
        if ($this->opacity < 100) {
            return true;
        }
        
        try {
            $rgbColor = ColorMate::$plugin->color->getColor($this->baseColor)->getRgb();
        } catch (\Throwable $e) {
            return false;
        }

        return $rgbColor->getAlpha() < 1;
    }

}
