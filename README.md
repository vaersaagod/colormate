ColorMate plugin for Craft CMS
===

Color me impressed, mate!
  
![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 4.0.0 or later. 

## Installation

To install the plugin, either install it from the plugin store, or follow these instructions:

1. Install with composer via `composer require vaersaagod/colormate` from your project directory.
2. Install the plugin in the Craft Control Panel under Settings → Plugins, or from the command line via `./craft install/plugin colormate`.

---

## Configuring

ToolMate is configured by creating a file named `colormate.php` in your Craft config folder, 
and configuring as needed. Sample config:

```
<?php

return [
    '*' => [
        'presets' => [
            'ctaColors' => [
                'name' => 'CTA Colors',
                'viewMode' => \vaersaagod\colormate\fields\ColorMateField::FIELD_VIEW_MODE_EXPANDED,
                'showCustom' => true,
                'showOpacity' => true,
                'showClear' => false,
                'colors' => [
                    'blush' => [
                        'name' => 'Blush',
                        'color' => '#eea8bf'
                    ],
                    'mustard' => [
                        'name' => 'Mustard',
                        'color' => '#ead30a'
                    ],
                    'baby' => [
                        'name' => 'Baby',
                        'color' => '#67cdfc'
                    ],
                    'transred' => [
                        'name' => 'Lorem',
                        'color' => 'rgba(255, 0, 0, 0.4)'
                    ],
                ],
                'default' => 'blush'
            ],

            'overlayColors' => [
                'name' => 'Overlay Colors',
                'viewMode' => \vaersaagod\colormate\fields\ColorMateField::FIELD_VIEW_MODE_COMPACT,
                'showCustom' => false,
                'showOpacity' => false,
                'showClear' => true,
                'colors' => [
                    'black-10p' => [
                        'name' => '10% black',
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'black-20p' => [
                        'name' => '20% black',
                        'color' => 'rgba(0, 0, 0, 0.2)',
                    ],
                    'black-30p' => [
                        'name' => '30% black',
                        'color' => 'rgba(0, 0, 0, 0.3)',
                    ]
                ]
            ]
        ]
    ]
];
```

--- 

## Field type

The field type returns a [Color](https://github.com/vaersaagod/colormate/blob/master/src/models/Color.php) 
model with the following properties and methods:

### handle

Handle of preset color if the chosen color was from a preset.

### custom

Custom color value.

### opacity

Opacity value.

### name

Name of preset color if the chosen color was from a preset.

### baseColor

The calculated base color, either the custom one, or the color
value from the selected preset color.

### preset

The preset that was used for the field.

### getColor([format = 'rgb'])

Returns the resulting color, either a custom color value or from a preset, 
with opacity factored into it. 

### isCustom()

Is the color a custom one?

### hasTransparency()

Does the resulting color have transparency?

---

## Template variables / Service methods

All the following methods are available both as template variables, using 
`craft.colormate.<method>(<args>)`, and as service methods, using
`ColorMate::$plugin->color-><method>(<args>)`. 

Most methods are wrappers for `[ssnepenthe/color-utils](https://github.com/ssnepenthe/color-utils)`,
refer to it for additional documentation.

### getColor(color)

### adjustColor(color, adjustment)

### getBrightness(color)

### getHue(color)

### getLightness(color)

### isBright(color[, threshold = 127.5])

### isLight(color[, threshold = 50])

### looksBright(color[, threshold = 127.5])

### getPercievedBrightness(color)

### getRelativeLuminance(color)

### getSaturation(color)

### getBrightnessDifference(color1, color2)

### getColorDifference(color1, color2)

### getContrastRatio(color1, color2)

### rgb2hex(color)

### hex2rgb(hexValue)


---

## Price, license and support

The plugin is released under the MIT license. It's made for Værsågod and friends, and no support 
is given. Submitted issues are resolved if it scratches an itch. 

## Changelog

See [CHANGELOG.MD](https://raw.githubusercontent.com/vaersaagod/colormate/master/CHANGELOG.md).

## Credits

Brought to you by [Værsågod](https://www.vaersaagod.no)

Icon designed by [Freepik from Flaticon](https://www.flaticon.com/authors/freepik).

