<template>
    <div class="colormate-field" ref="wrapper">
        <ul v-if="colors.length > 0">
            <li v-for="color in colors">
                <div class="colormate-field__color" v-bind:class="{ '-selected-color': color.handle === selectedColorHandle }" v-on:click="onColorClick(color)">
                    <span class="colormate-field__color-inner" v-bind:style="'background-color:' + color.color + ';' + ' opacity:' + parsedGlobalOpacity + ';'"></span>
                    <span class="colormate-field__color-name">{{ color.name }}</span>
                </div>
            </li>
        </ul>

        <div class="colormate-field__inputs input" v-if="showCustom || showOpacity">
            <div class="colormate-field__color -custom-color" v-bind:class="{ '-selected-color': parsedCustomColor !== null }" v-if="showCustom">
                <span class="colormate-field__color-inner" v-bind:style="'background-color:' + (parsedCustomColor ? parsedCustomColor : '#fff') + ';' + ' opacity:' + parsedGlobalOpacity + ';'"></span>
            </div>
            <div class="colormate-field__input-wrap" v-if="showCustom">
                <input ref="customColorInput" class="colormate-field__input-color text" type="text" placeholder="#000000" v-model="customColorInput" maxlength="7">
            </div>
            <div class="colormate-field__input-wrap" v-if="showOpacity">
                <input ref="customOpacityInput" class="colormate-field__input-opacity text" type="number" value="100" min="0" max="100" maxlength="3" v-model="customOpacityInput">
            </div>
            <div class="colormate-field__clear" v-on:click="onClearClick" v-if="(selectedColorHandle !== '' && selectedColorHandle !== presetConfig.default) || customColor !== '' || parseInt(customOpacityInput) !== 100">
                <div class="colormate-field__clear-inner"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import colorString from 'color-string';

    export default {
        name: "ColorMateField.vue",
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
            }
        },
        watch: {
            customColorInput(val) {
                if (val !== '' && this.selectedColorHandle !== '') {
                    this.tempSelectedColorHandle = this.selectedColorHandle;
                    this.selectedColorHandle = '';
                }

                /*
                if (val === '' && this.tempSelectedColorHandle !== '') {
                    this.selectedColorHandle = this.tempSelectedColorHandle;
                }
                */

                if (val !== '' && val.charAt(0) !== '#') {
                    this.customColorInput = '#' + val;
                }

                this.customColor = val;
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
        data: function () {
            return {
                viewMode: '',
                showCustom: false,
                showOpacity: false,
                colors: [],
                selectedColorHandle: '',
                tempSelectedColorHandle: '',
                customColor: '',

                customColorInput: '',
                customOpacityInput: '100',

                handleInputId: '',
                customInputId: '',
                opacityInputId: '',
            }
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
        }
    }
</script>

<style lang="scss">
    .colormate-field {
        display: flex;
        align-items: flex-start;

        * {
            box-sizing: border-box;
        }

        li {
            display: inline-block;
        }

        &__color {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 34px;
            box-shadow: inset 0 10px 20px rgba(255, 255, 255, 0.3), inset 0 -10px 20px rgba(0, 0, 0, 0.07);
            margin-right: 5px;
            background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill-rule='nonzero' fill='none'%3E%3Cpath id='Rectangle' fill='%23EAECEE' d='M0 0h18v18H0z'/%3E%3Cpath id='Rectangle-Copy' fill='%23D5D8DD' d='M0 0h9v9H0z'/%3E%3Cpath id='Rectangle-Copy-4' fill='%23D5D8DD' d='M8 9h9v9H9z'/%3E%3C/g%3E%3C/svg%3E");
            background-repeat: repeat;
            background-position: center;
            border-radius: 5px;
            cursor: pointer;
            overflow: hidden;

            * {
                pointer-events: none;
            }

            &-name {
                display: none;
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
            }

            &.-selected-color:after {
                top: 50%;
            }
        }

        &__inputs {
            margin-left: 12px;
            display: flex;
        }

        &__input-wrap {
        }

        &__input-color {
            width: 85px;

        }

        &__input-opacity {
            width: 60px;
            margin-left: 17px;
        }

        &__clear {
            cursor: pointer;
            padding: 5px;
            margin-left: 12px;
            margin-top: 3px;

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
