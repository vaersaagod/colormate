import Vue from 'vue';

import ColorMateField from './components/ColorMateField.vue';

import './css/field.css';

window.Craft.ColorMateField = config => {
    new Vue({
        el: config.appId,
        components: {
            ColorMateField
        },
        data: {
            baseInputId: config.baseInputId,
            presetConfig: config.presetConfig,
            fieldValue: config.fieldValue
        },
        mounted() {
        }
    });
};
