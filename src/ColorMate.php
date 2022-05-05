<?php
/**
 * ColorMate plugin for Craft CMS 4.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2022 Værsågod
 */

namespace vaersaagod\colormate;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;

use vaersaagod\colormate\fields\ColorMateField;
use vaersaagod\colormate\models\Settings;

use vaersaagod\colormate\services\ColorService;
use vaersaagod\colormate\variables\ColorMateVariable;
use yii\base\Event;

/**
 * Class ColorMate
 *
 * @author    Værsågod
 * @package   ColorMate
 * @since     1.0.0
 *
 * @property  ColorService $color
 */
class ColorMate extends Plugin
{
    // Static Properties
    // =========================================================================

    public static ColorMate $plugin;

    // Public Properties
    // =========================================================================
    
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Register services
        $this->setComponents([
            'color' => ColorService::class,
        ]);
        
        // Register field type
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            [$this, 'onRegisterFieldTypes']
        );

        // Add template variable
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            static function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('colormate', ColorMateVariable::class);
            }
        );
    }
    
    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * @param RegisterComponentTypesEvent $event
     */
    public function onRegisterFieldTypes(RegisterComponentTypesEvent $event): void
    {
        $event->types[] = ColorMateField::class;
    }

}
