<template>
    <div class="colormate-field" v-bind:class="{ 'colormate-field--has-no-palette': !hasPalette }" ref="wrapper">
        <ul class="colormate-field__colors" v-if="colors.length > 0">
            <li class="colormate-field__colors-item" v-for="color in colors">
                <button type="button" class="colormate-field__color" v-bind:class="{ '-selected-color': color.handle === selectedColorHandle }" v-on:click="onColorClick(color)">
                    <span class="colormate-field__color-inner" v-bind:style="'background-color:' + color.color + ';' + ' opacity:' + parsedGlobalOpacity + ';'"></span>
                </button>
                <craft-tooltip placement="top" v-if="showTooltip">{{ color.name }}</craft-tooltip>
            </li>
        </ul>

        <div class="colormate-field__inputs input" v-if="showCustom || showOpacity || showClear">
            <div class="colormate-field__input" v-if="showCustom">
                <div class="colormate-field__color colormate-field__input-custom-color" v-bind:class="{ '-selected-color': parsedCustomColor !== null }">
                    <span class="colormate-field__color-inner" v-bind:style="'background-color:' + (parsedCustomColor ? parsedCustomColor : '#fff') + ';' + ' opacity:' + parsedGlobalOpacity + ';'"></span>
                </div>
                <input ref="customColorPickerInput" class="colormate-field__color-picker-input" type="color" v-model="customColorPickerInput">
                <input ref="customColorInput" class="colormate-field__input-color text" type="text" placeholder="#000000" v-model="customColorInput" maxlength="7">
                <craft-tooltip placement="top" v-if="showTooltip">{{ $Craft.t('colormate', 'Custom color') }}</craft-tooltip>
            </div>

            <div class="colormate-field__input" v-if="showOpacity">
                <input ref="customOpacityInput" class="colormate-field__input-opacity text" type="number" value="100" min="0" max="100" maxlength="3" v-model="customOpacityInput">
                <craft-tooltip placement="top" v-if="showTooltip">{{ $Craft.t('colormate', 'Opacity') }}</craft-tooltip>
            </div>

            <button type="button" class="colormate-field__clear" v-on:click="onClearClick" v-if="doShowClear">
                <div class="colormate-field__clear-inner"></div>
            </button>
        </div>
    </div>
</template>

<script>
import colorString from 'color-string';

export default {
    name: 'ColorMateField.vue',
    props: {
        baseInputId: String,
        presetConfig: Object,
        fieldValue: Object
    },
    components: {},
    computed: {
        parsedCustomColor() {
            if (this.customColorInput === '') {
                return null;
            }

            return this.customColorInput;
        },
        parsedGlobalOpacity() {
            if (this.customOpacityInput === '') {
                return 1;
            }

            return (this.customOpacityInput / 100);
        },
        hasPalette() {
            return this.colors.length > 0;
        },
        doShowClear() {
            return this.showClear && ((this.selectedColorHandle !== '' && this.selectedColorHandle !== this.presetConfig.default) || this.customColor !== '' || parseInt(this.customOpacityInput) !== 100);
        }
    },
    watch: {
        customColorInput(val) {
            if (val !== '' && this.selectedColorHandle !== '') {
                this.tempSelectedColorHandle = this.selectedColorHandle;
                this.selectedColorHandle = '';
            }

            if (val !== '' && val.charAt(0) !== '#') {
                this.customColorInput = '#' + val;
            }

            this.customColor = val;

            if (this.customColor.length >= 7) {
                this.customColorPickerInput = this.customColor;
            }
            this.customColorInput = this.customColor;
        },
        customColorPickerInput(val) {
            this.customColorInput = val;
        },
        selectedColorHandle(val) {
            document.getElementById(this.handleInputId).value = val;

            if (val !== '' && this.customColorInput !== '') {
                this.tempSelectedColorHandle = '';
                this.customColorInput = '';
            }
        },
        customColor(val) {
            document.getElementById(this.customInputId).value = val;
        },
        customOpacityInput(val) {
            document.getElementById(this.opacityInputId).value = val;
        }
    },
    data: function() {
        return {
            viewMode: '',
            showCustom: false,
            showOpacity: false,
            showClear: false,
            showTooltip: true,
            colors: [],
            selectedColorHandle: '',
            tempSelectedColorHandle: '',
            customColor: '',

            customColorInput: '',
            customColorPickerInput: '#000000',
            customOpacityInput: '100',

            handleInputId: '',
            customInputId: '',
            opacityInputId: ''
        };
    },
    methods: {
        onColorClick(color) {
            this.selectedColorHandle = color.handle;
        },
        onClearClick() {
            this.selectedColorHandle = this.presetConfig.default !== '' ? this.presetConfig.default : '';
            this.tempSelectedColorHandle = '';
            this.customColorInput = '';
            this.customOpacityInput = '100';
        }
    },
    mounted() {
        this.colors = this.presetConfig.colors;
        this.showCustom = this.presetConfig.showCustom;
        this.showOpacity = this.presetConfig.showOpacity;
        this.showClear = this.presetConfig.showClear;
        this.showTooltip = this.presetConfig.showTooltip;

        this.handleInputId = this.baseInputId + '-handle';
        this.customInputId = this.baseInputId + '-custom';
        this.opacityInputId = this.baseInputId + '-opacity';

        if (this.fieldValue) {
            if (this.fieldValue.handle && this.fieldValue.handle !== '') {
                this.selectedColorHandle = this.fieldValue.handle;
            }

            if (this.fieldValue.custom && this.fieldValue.custom !== '') {
                if (this.fieldValue.custom.startsWith('rgb')) {
                    const color = colorString.get(this.fieldValue.custom);
                    this.customColorInput = color ? colorString.to.hex(color.value) : '';
                } else {
                    this.customColorInput = this.fieldValue.custom;
                }
            }

            if (this.fieldValue.opacity && this.fieldValue.opacity !== '') {
                this.customOpacityInput = this.fieldValue.opacity;
            }
        }

        // Prevent unwanted window unload confirm dialogs due to Craft serializing the initial DOM state before ColorMate boots up
        requestAnimationFrame(() => {
            var $editor = $(this.$el)
                .closest('.element-editor,.hud');
            if ($editor.length) {
                this.elementEditor = $editor.data('elementEditor') || {};
                if (this.elementEditor.slideout) {
                    this.elementEditor.initialData = this.elementEditor.slideout.$container.serialize();
                } else if (this.elementEditor.hud) {
                    this.elementEditor.initialData = this.elementEditor.hud.$body.serialize();
                }
            }
        });
    }
};
</script>

<style lang="scss">
.colormate-field {
    display: flex;

    * {
        box-sizing: border-box;
    }

    &__colors {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: -5px;

        &-item {
            display: block;
            position: relative;
            margin-right: 5px;
            margin-bottom: 5px;
            &:last-child {
                margin-right: 0;
            }
        }
    }

    &__color {
        position: relative;
        display: block;
        width: 34px;
        height: 34px;
        box-shadow: inset 0 10px 20px rgba(255, 255, 255, 0.3), inset 0 -10px 20px rgba(0, 0, 0, 0.07);
        background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill-rule='nonzero' fill='none'%3E%3Cpath id='Rectangle' fill='%23EAECEE' d='M0 0h18v18H0z'/%3E%3Cpath id='Rectangle-Copy' fill='%23D5D8DD' d='M0 0h9v9H0z'/%3E%3Cpath id='Rectangle-Copy-4' fill='%23D5D8DD' d='M8 9h9v9H9z'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: repeat;
        background-position: center;
        border-radius: 5px;
        cursor: pointer;
        overflow: hidden;
        flex: none;

        * {
            pointer-events: none;
        }

        &:not(.-selected-color) {
            .colormate-field__color-inner {
                opacity: 1 !important;
            }
        }

        &-inner {
            display: block;
            position: absolute;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            overflow: hidden;
            transition: background-color 0.2s ease;
        }

        &-picker-input {
            display: block;
            position: absolute;
            width: 34px;
            height: 34px;
            left: 0;
            top: 0;
            z-index: 3;
            opacity: 0;
            cursor: pointer;
        }

        &:after {
            content: '';
            display: block;
            position: absolute;
            z-index: 2;
            background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='utf-8'%3F%3E%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 22 22' style='enable-background:new 0 0 22 22;' xml:space='preserve'%3E%3Cg%3E%3Ccircle style='opacity:0.25;' cx='11' cy='11' r='11'/%3E%3Cpolygon style='fill:%23FFFFFF;' points='9.4,15.8 4.7,11 6.3,9.4 9.4,12.5 15.3,6.6 16.9,8.3 '/%3E%3C/g%3E%3C/svg%3E%0A");
            width: 22px;
            height: 22px;
            left: 50%;
            top: 150%;
            margin: -11px 0 0 -11px;
            transition: top 0.5s cubic-bezier(0.230, 1.000, 0.320, 1.000);

            .colormate-field--has-no-palette & {
                display: none;
            }
        }


        &.-selected-color:after {
            top: 50%;

        }
    }

    &__colors + &__inputs {
        margin-left: 10px;
    }

    &__inputs {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        margin-bottom: -5px;
    }

    &__input {
        position: relative;
        display: flex;
        margin-right: 10px;
        margin-bottom: 5px;
        &:last-child {
            margin-right: 0;
        }
        &-color {
            width: 85px;
        }
        &-custom-color {
            margin-right: 3px;
        }
        &-opacity {
            width: 60px;
        }
    }

    &__clear {
        cursor: pointer;
        padding: 5px;
        align-self: center;
        margin-bottom: 5px;

        &:hover {
            .colormate-field__clear-inner {
                background-color: rgba(0, 0, 0, 0.4);
            }
        }

        &-inner {
            position: relative;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.15);
            transition: background-color 0.1s ease;

            &:after {
                content: '';
                display: block;
                position: absolute;
                width: 10px;
                height: 2px;
                left: 5px;
                top: 9px;
                background-color: #fff;
            }
        }
    }
}
</style>
