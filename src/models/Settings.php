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
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public $presets = [];

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
     * @return Preset|null
     */
    public function getPresetByHandle($handle)
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
