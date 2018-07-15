/* global Vue, */
import FunctionSelect from '../../components/function-select.vue';
// @vue/component
const vm = {

    el: '#datacollectief-settings',

    name: 'DatacollectiefSettings',

    components: {
        'function-select': FunctionSelect,
    },

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
        setImportFunctions(value) {
            this.config.wl_import_functions = value;
        },
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
