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
    public function getPresets(): array
    {
        $r = [];

        foreach ($this->presets as $handle => $preset) {
            $r[] = new Preset(array_merge(
                $preset,
                ['handle' => $handle]
            ));
        }

        return $r;
    }

    /**
     * @param string $handle
     *
     * @return Preset|null
     */
    public function getPresetByHandle(string $handle): ?Preset
    {
        if (!isset($this->presets[$handle])) {
            return null;
        }

        return new Preset(array_merge(
            $this->presets[$handle],
            ['handle' => $handle]
        ));
    }

}
