
module.exports = {

    name: 'datacollectief-index',

    el: '#datacollectief-index',

    data() {
        return _.merge({
            config: {},
        }, window.$data);
    },

    created() {
    },

    methods: {
    }

};

Vue.ready(module.exports);
