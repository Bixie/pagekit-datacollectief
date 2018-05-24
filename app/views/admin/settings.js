
module.exports = {

    name: 'datacollectief-settings',

    el: '#datacollectief-settings',

    fields: require('../../settings/fields'),

    data() {
        return _.merge({
            config: {},
            form: {},
        }, window.$data);
    },

    methods: {
        save() {
            this.$http.post('admin/datacollectief/config', {config: this.config}).then(() => {
                this.$notify('Settings saved.');
            }, res => this.$notify((res.data.message || res.data), 'danger'));
        },
    }

};

Vue.ready(module.exports);
