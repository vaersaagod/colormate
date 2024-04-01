<?php
/**
 * ColorMate plugin for Craft CMS 5.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
 */

namespace vaersaagod\colormate\fields;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\errors\InvalidFieldException;
use craft\helpers\Html;
use craft\helpers\Json;

use vaersaagod\colormate\assetbundles\ColorMateFieldBundle;
use vaersaagod\colormate\ColorMate;
use vaersaagod\colormate\models\Color;
use vaersaagod\colormate\models\Settings;

use Throwable;
use yii\db\Schema;

/**
 * Class ColorMateField
 *
 * @package ColorMate
 *
 * @property string $contentColumnType
 * @property-read string[] $elementValidationRules
 * @property string $settingsHtml
 */
class ColorMateField extends Field implements PreviewableFieldInterface
{

    /** @var string */
    public const FIELD_VIEW_MODE_COMPACT = 'compact';

    /** @var string */
    public const FIELD_VIEW_MODE_EXPANDED = 'expanded';

    /** @var string */
    public string $preset = '';

    /** @var string hex|rgb|handle|name|colorOnly */
    public string $previewMode = 'hex';

   
    /**
     * @inerhitdoc 
     */
    public static function icon(): string
    {
        return 'palette';
    }

    /**
     * @return string
     */
    public static function displayName(): string
    {
        return Craft::t('colormate', 'ColorMate');
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     *
     * @return mixed
     */
    public function normalizeValue(mixed $value, ElementInterface $element = null): mixed
    {
        if ($value instanceof Color) {
            return $value;
        }

        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        }

        if (!is_array($value)) {
            $value = [];
        }

        return $this->createColorModel($value, $element->site->handle ?? null);
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     *
     * @return array|mixed|string|null
     */
    public function serializeValue(mixed $value, ElementInterface $element = null): mixed
    {
        return parent::serializeValue(array_filter(
            $value instanceof Color ? $value->getAttributes() : $value,
            static function ($key) {
                return in_array($key, [
                    'handle',
                    'custom',
                    'opacity',
                ]);
            },
            ARRAY_FILTER_USE_KEY
        ), $element);
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     *
     * @return string
     * @throws Throwable
     */
    public function getInputHtml(mixed $value, ElementInterface $element = null): string
    {
        /** @var Settings $pluginSettings */
        $pluginSettings = ColorMate::$plugin->getSettings();
        $settings = $this->getSettings();
        $fieldPreset = $settings['preset'];
        $presetConfig = $pluginSettings->getPresetByHandle($fieldPreset, $element->site->handle ?? null);

        if ($presetConfig) {
            $presetConfig->colors = $presetConfig->getColors();
        }

        Craft::$app->getView()->registerAssetBundle(ColorMateFieldBundle::class);
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        return Craft::$app->getView()->renderTemplate('colormate/field/_input', [
            'name' => $this->handle,
            'nameNs' => Craft::$app->view->namespaceInputId($this->handle),
            'id' => $id,
            'namespacedId' => $namespacedId,
            'settings' => $this->getSettings(),
            'value' => $value->getAttributes(),
            'model' => $value,
            'presetConfig' => $presetConfig?->getAttributes(),
        ]);
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function getSettingsHtml(): string
    {
        /** @var Settings $pluginSettings */
        $pluginSettings = ColorMate::$plugin->getSettings();

        $presets = $pluginSettings->getPresets();
        $presetOptions = $this->getPresetSelectOptions($presets);

        return Craft::$app->getView()->renderTemplate('colormate/field/_settings', [
            'field' => $this,
            'presetOptions' => $presetOptions
        ]);
    }

    /**
     * @inheritDoc
     */
    public function isValueEmpty($value, ElementInterface $element): bool
    {
        return empty($value->handle) && empty($value->custom);
    }

    /**
     * @inheritDoc
     */
    public function getElementValidationRules(): array
    {
        return ['validateField'];
    }

    /**
     * @param Element $element
     * @return void
     * @throws InvalidFieldException
     */
    public function validateField(Element $element): void
    {
        if ($element->getScenario() === Element::SCENARIO_LIVE) {
            $fieldValue = $element->getFieldValue($this->handle);

            if ($fieldValue && !$fieldValue->validate()) {
                $element->addModelErrors($fieldValue, $this->handle);
            }
        }
    }

    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        if (!$value instanceof Color) {
            return Html::tag('div', Html::tag('div', ''), [
                'class' => 'color small static',
            ]);
        }

        $html = Html::tag('div', Html::tag('div', null, [
            'class' => 'color-preview',
            'style' => [
                'background-color' => $value->getColor('hex'),
            ]
        ]), [
            'class' => 'color small static',
            'style' => 'flex: none;',
        ]);

        if ($this->previewMode === 'colorOnly') {
            return $html;
        }

        if ($this->previewMode === 'handle') {
            $label = $value->handle;
        } else if ($this->previewMode === 'name') {
            $label = Craft::t('site', $value->name);
        } else if ($this->previewMode === 'rgb') {
            $label = $value->getColor('rgb');
        } else {
            $label = $value->getColor('hex');
        }

        return
            Html::tag('div', $html . Html::tag('div', $label, [
                    'class' => 'colorhex code',
                ]), [
                'style' => 'display:flex;',
            ]);
    }

    /**
     * --- Private functions -------------------------------------------------------
     */

    /**
     * @param array $presets
     *
     * @return array
     */
    private function getPresetSelectOptions(array $presets): array
    {
        $opts = [];

        foreach ($presets as $value) {
            $opts[] = [
                'value' => $value['handle'],
                'label' => $value['name']
            ];
        }

        return $opts;
    }

    /**
     * @param array $value
     * @param string|null $siteHandle
     *
     * @return Color
     */
    private function createColorModel(array $value, string $siteHandle = null): Color
    {
        /** @var Settings $pluginSettings */
        $pluginSettings = ColorMate::$plugin->getSettings();
        $settings = $this->getSettings();
        $fieldPreset = $settings['preset'];
        $presetConfig = $pluginSettings->getPresetByHandle($fieldPreset, $siteHandle);

        if (!isset($value['opacity']) || $value['opacity'] === '') {
            $value['opacity'] = 100;
        }

        $value['opacity'] = (int)$value['opacity'];

        $colorModel = new Color(array_filter(
            $value,
            static function ($key) {
                return in_array($key, [
                    'handle',
                    'custom',
                    'opacity',
                ]);
            },
            ARRAY_FILTER_USE_KEY
        ));

        $colorModel->preset = $presetConfig;

        if ($colorModel->handle !== '' && $presetConfig) {
            $color = $presetConfig->getColorByHandle($colorModel->handle);

            if ($color) {
                $colorModel->baseColor = $color->color;
                $colorModel->name = $color->name;
            } else {
                $colorModel->baseColor = null;
            }
        } else if ($colorModel->custom !== '') {
            $colorModel->baseColor = $colorModel->custom;
        }

        if ($colorModel->baseColor === null && $presetConfig && $presetConfig->default) {
            $color = $presetConfig->getColorByHandle($presetConfig->default);

            if ($color) {
                $colorModel->baseColor = $color->color;
                $colorModel->name = $color->name;
                $colorModel->handle = $color->handle;
            } else {
                Craft::error('Unknown default value "' . $presetConfig->default . '" in preset "' . $presetConfig->name . '"', __METHOD__);
                $colorModel->baseColor = null;

            }
        }

        return $colorModel;
    }

}
