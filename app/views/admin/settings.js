/* global Vue, */

// @vue/component
const vm = {

    el: '#datacollectief-settings',

    name: 'SatacollectiefSettings',

    fields: require('../../settings/fields'),

    data() {
        const {config, indications,} = window.$data;
        return {
            config,
            indications,
            form: {},
        };
    },

    methods: {
        save() {
            this.$http.post('admin/datacollectief/config', {config: this.config,}).then(() => {
                this.$notify('Settings saved.');
            }, res => this.$notify((res.data.message || res.data), 'danger'));
        },
    },

};

Vue.ready(vm);
