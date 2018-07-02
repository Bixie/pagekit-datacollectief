/* global Vue, */

// @vue/component
const vm = {

    el: '#datacollectief-settings',

    name: 'DatacollectiefSettings',

    fields: require('../../settings/fields'),

    data() {
        const {config, indications,} = window.$data;
        return {
            config,
            indications,
            apiInfo: {
                version: '',
            },
            form: {},
        };
    },

    created() {
        this.Api = this.$resource('api/datacollectief', {}, {
            'info': {method: 'get', url: 'api/datacollectief/info',},
            'baseTable': {method: 'get', url: 'api/datacollectief/baseTable{/table}',},
        });
    },

    methods: {
        getApiInfo() {
            this.Api.info().then(res => {
                this.apiInfo = res.data.apiInfo;
            }, res => this.$notify((res.data.message || res.data), 'danger'));

        },
        getBaseTable(table, identifier) {
            this.Api.baseTable({table,}, {identifier,}).then(res => {
                this.config[table] = res.data.baseTable;
                this.save();
            }, res => this.$notify((res.data.message || res.data), 'danger'));

        },
        save() {
            this.$http.post('admin/datacollectief/config', {config: this.config,}).then(() => {
                this.$notify('Settings saved.');
            }, res => this.$notify((res.data.message || res.data), 'danger'));
        },
    },

};

Vue.ready(vm);
