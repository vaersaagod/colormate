<?php
/**
 * ColorMate plugin for Craft CMS 5.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
 */

namespace vaersaagod\colormate\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ColorMateFieldBundle extends AssetBundle
{
    /**
     * @inheritDoc 
     */
    public function init(): void
    {
        // Path to publishable resources
        $this->sourcePath = '@vaersaagod/colormate/assetbundles/dist';

        // Dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // Assets
        $this->js = [
            'field.js',
        ];

        $this->css = [
            'field.css',
        ];

        parent::init();
    }
}
