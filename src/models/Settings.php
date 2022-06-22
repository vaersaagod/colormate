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

use craft\base\Model;

/**
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public array $presets = [];

    // Public Methods
    // =========================================================================
    
    /**
     * @return Preset[]
     */
    public function getPresets(string $siteHandle = null): array
    {
        $r = [];

        foreach ($this->presets as $handle => $preset) {
            $r[] = new Preset(array_merge(
                $this->getLocalizedPreset($preset, $siteHandle),
                ['handle' => $handle]
            ));
        }

        return $r;
    }

    public function getPresetByHandle(string $handle, string $siteHandle = null): ?Preset
    {
        if (!isset($this->presets[$handle])) {
            return null;
        }
        
        if ($siteHandle === null) {
            $siteHandle = \Craft::$app->getSites()->getCurrentSite()->handle;
        }

        return new Preset(array_merge(
            $this->getLocalizedPreset($this->presets[$handle], $siteHandle),
            ['handle' => $handle]
        ));
    }
    
    public function getLocalizedPreset(array $value, string $siteHandle = null)
    {
        if ($siteHandle === null) {
            $siteHandle = \Craft::$app->getSites()->getCurrentSite()->handle;
        }
        
        if (array_key_exists($siteHandle, $value) && is_array($value[$siteHandle])) {
            return $value[$siteHandle];
        }
        
        if (array_key_exists('*', $value) && is_array($value['*'])) {
            return $value['*'];
        }
        
        return $value;
    }
}
