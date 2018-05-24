/* global Vue, */

// @vue/component
const vm = {

    el: '#datacollectief-index',

    name: 'DatacollectiefIndex',

    data() {
        const {config,} = window.$data;
        return {
            loading: false,
            websites: [],
            leads: [],
            wl_options: {
                From: new Date(config.wl_last_checked),
                To: new Date(),
                Website: '',
            },
            config,
        };
    },

    watch: {
        'wl_options': {
            handler() {
                this.getWebsiteleads();
            },
            deep: true,
        },
    },

    created() {
        this.Api = this.$resource('api/datacollectief', {}, {
            'websites': {method: 'get', url: 'api/datacollectief/websiteleads/websites',},
            'leads': {method: 'get', url: 'api/datacollectief/websiteleads/leads',},
        });
        this.Api.websites().then(res => {
            this.websites = res.data.websites;
        }, res => this.$notify((res.data.message || res.data), 'danger'));
    },

    methods: {
        getWebsiteleads() {
            this.loading = true;
            this.Api.leads({}, {options: this.wl_options,}).then(res => {
                console.log(res.data);
                this.leads = res.data.leads;
            }, res => this.$notify((res.data.message || res.data), 'danger'))
                .then(() => this.loading = false);
        },
    },

};

Vue.ready(vm);
