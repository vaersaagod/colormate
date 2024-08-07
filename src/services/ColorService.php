<?php
/**
 * ColorMate plugin for Craft CMS 5.x
 *
 * Color me impressed, mate!
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
 */

namespace vaersaagod\colormate\services;

use craft\base\Component;

use SSNepenthe\ColorUtils\Colors\Color;
use vaersaagod\colormate\models\Color as ColorMateColor;
use function SSNepenthe\ColorUtils\{
    adjust_color,
    adjust_hue,
    alpha,
    blue,
    brightness,
    brightness_difference,
    color,
    color_difference,
    contrast_ratio,
    green,
    hsl,
    hsla,
    hue,
    is_bright,
    is_light,
    lightness,
    looks_bright,
    name,
    opacity,
    perceived_brightness,
    red,
    relative_luminance,
    rgb,
    rgba,
    saturation
};

class ColorService extends Component
{
    /**
     * @param array|string $c
     *
     * @return Color
     */
    public function getColor(array|string $c): Color
    {
        return $this->normalizeColor($c);
    }

    /**
     * @param array|string|Color|ColorMateColor $color
     * @param array                             $adjustment
     *
     * @return Color
     */
    public function adjustColor(array|ColorMateColor|Color|string $color, array $adjustment): Color
    {
        $c = $this->normalizeColor($color);
        return adjust_color($c, $adjustment);
    }

    /**
     * Calculates color brightness (https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 (black) to 255 (white). 
     * 
     * @param array|string|Color|ColorMateColor $color
     *
     * @return float
     */
    public function getBrightness(array|ColorMateColor|Color|string $color): float
    {
        $c = $this->normalizeColor($color);
        return brightness($c);
    }

    /**
     * Get the hue channel of a color.
     * 
     * @param array|string|Color|ColorMateColor $color
     *
     * @return float
     */
    public function getHue(array|ColorMateColor|Color|string $color): float
    {
        $c = $this->normalizeColor($color);
        return hue($c);
    }

    /**
     * Get the lightness channel of a color
     * 
     * @param array|string|Color|ColorMateColor $color
     *
     * @return float
     */
    public function getLightness(array|ColorMateColor|Color|string $color): float
    {
        $c = $this->normalizeColor($color);
        return lightness($c);
    }

    /**
     * Checks brightness($color) >= $threshold. Accepts an optional $threshold float as the last parameter with a default of 127.5. 
     *
     * @param array|string|Color|ColorMateColor $color
     * @param float                             $threshold
     *
     * @return bool
     */
    public function isBright(array|ColorMateColor|Color|string $color, float $threshold=127.5): bool
    {
        $c = $this->normalizeColor($color);
        return is_bright($c, $threshold);
    }

    /**
     * Checks lightness($color) >= $threshold. Accepts an optional $threshold float as the last parameter with a default of 50.0. 
     * 
     * @param array|string|Color|ColorMateColor $color
     * @param int                               $threshold
     *
     * @return bool
     */
    public function isLight(array|ColorMateColor|Color|string $color, int $threshold=50): bool
    {
        $c = $this->normalizeColor($color);
        return is_light($c, $threshold);
    }

    /**
     * Checks perceived_brightness($color) >= $threshold. Accepts an optional $threshold float as the last parameter with a default of 127.5. 
     * 
     * @param array|string|Color|ColorMateColor $color
     * @param float                             $threshold
     *
     * @return bool
     */
    public function looksBright(array|ColorMateColor|Color|string $color, float $threshold = 127.5): bool
    {
        $c = $this->normalizeColor($color);
        return looks_bright($c, $threshold);
    }

    /**
     * Calculates the perceived brightness (http://alienryderflex.com/hsp.html) of a color on a scale from 0 (black) to 255 (white).
     * 
     * @param array|string|Color|ColorMateColor $color
     *
     * @return float
     */
    public function getPercievedBrightness(array|ColorMateColor|Color|string $color): float 
    {
        $c = $this->normalizeColor($color);
        return perceived_brightness($c);
    }

    /**
     * Calculates the relative luminance (https://www.w3.org/TR/WCAG20/#relativeluminancedef) of a color on a scale from 0 (black) to 1 (white).
     * 
     * @param array|string|Color|ColorMateColor $color
     *
     * @return float
     */
    public function getRelativeLuminance(array|ColorMateColor|Color|string $color): float 
    {
        $c = $this->normalizeColor($color);
        return relative_luminance($c);
    }

    /**
     * Get the saturation channel of a color.
     * 
     * @param string|array|Color|ColorMateColor $color
     * @return float
     */
    public function getSaturation(array|ColorMateColor|Color|string $color): float 
    {
        $c = $this->normalizeColor($color);
        return saturation($c);
    }

    /**
     * Calculates brightness difference (https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 to 255.
     * 
     * @param string|array|Color|ColorMateColor $color1
     * @param string|array|Color|ColorMateColor $color2
     * @return float
     */
    public function getBrightnessDifference(array|ColorMateColor|Color|string $color1, array|ColorMateColor|Color|string $color2): float
    {
        $c1 = $this->normalizeColor($color1);
        $c2 = $this->normalizeColor($color2);
        return brightness_difference($c1, $c2);
    }

    /**
     * Calculates color difference (https://www.w3.org/TR/AERT#color-contrast) on a scale from 0 to 765.
     * 
     * @param string|array|Color|ColorMateColor $color1
     * @param string|array|Color|ColorMateColor $color2
     * @return int
     */
    public function getColorDifference(array|ColorMateColor|Color|string $color1, array|ColorMateColor|Color|string $color2): int
    {
        $c1 = $this->normalizeColor($color1);
        $c2 = $this->normalizeColor($color2);
        return color_difference($c1, $c2);
    }

    /**
     * Calculates the contrast ratio (https://www.w3.org/TR/WCAG20/#contrast-ratiodef) between two colors on a scale from 1 to 21.
     * 
     * @param string|array|Color|ColorMateColor $color1
     * @param string|array|Color|ColorMateColor $color2
     * @return float
     */
    public function getContrastRatio(array|ColorMateColor|Color|string $color1, array|ColorMateColor|Color|string $color2): float
    {
        $c1 = $this->normalizeColor($color1);
        $c2 = $this->normalizeColor($color2);
        return contrast_ratio($c1, $c2);
    }
    
    /**
     * Convert rgb color to hex
     *
     * @param string|array|Color|ColorMateColor $color
     *
     * @return string
     */
    public function rgb2hex(array|ColorMateColor|Color|string $color): string
    {
        $c = $this->normalizeColor($color);
        return '#' . sprintf('%02x', $c->getRgb()->getRed()) . sprintf('%02x', $c->getRgb()->getGreen()) . sprintf('%02x', $c->getRgb()->getBlue());
    }

    /**
     * Convert hex color to rgb
     *
     * @param string $hex
     *
     * @return array
     */
    public function hex2rgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);

        if (\strlen($hex) === 3) {
            $r = hexdec($hex[0] . $hex[0]);
            $g = hexdec($hex[1] . $hex[1]);
            $b = hexdec($hex[2] . $hex[2]);
        } else {
            $r = hexdec($hex[0] . $hex[1]);
            $g = hexdec($hex[2] . $hex[3]);
            $b = hexdec($hex[4] . $hex[5]);
        }

        return [$r, $g, $b];
    }

    /**
     * @param mixed $val
     * @return Color
     */
    private function normalizeColor(mixed $val): Color {
        if ($val instanceof Color) {
            return $val;
        }
        
        if ($val instanceof ColorMateColor) {
            return color($val->getColor());
        }
        
        return color($val);
    }
}
