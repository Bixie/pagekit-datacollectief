/* global Vue, */

// @vue/component
const vm = {

    el: '#datacollectief-index',

    name: 'DatacollectiefIndex',

    data() {
        const {config,} = window.$data;
        return {
            loading: false,
            websiteFields: [],
            processed_data: [],
            wl_options: {
                From: (new Date(config.wl_last_checked)).toISOString(),
                To: (new Date()).toISOString(),
                Website: '',
            },
            filter: {
                status: '',
            },
            config,
        };
    },

    computed: {
        websites() {
            const now = new Date();
            return this.websiteFields.filter(wF => {
                let startDate = new Date(`${wF.startDateField}T00:00:00+00:00`);
                let endDate = new Date(`${wF.endDateField}T00:00:00+00:00`);
                return startDate < now && (!wF.endDateField || endDate > now);
            }).map(wF => wF.nameField)
        },
        filteredProcessedData() {
            return this.processed_data.map(pd => pd.websiteleads).filter(pd => {
                if (this.filter.status === 'new') {
                    return pd.isNewCompany;
                } else if(this.filter.status === 'matched') {
                    return !pd.isNewCompany;
                } else if(this.filter.status === 'modified') {
                    return pd.changed_data.length > 0;
                }
                return true;
            })
        },
    },

    watch: {
        'wl_options.Website'() {
            this.getWebsiteleads();
        },
    },

    created() {
        this.Api = this.$resource('api/datacollectief', {}, {
            'websites': {method: 'get', url: 'api/datacollectief/websiteleads/websites',},
            'leads': {method: 'get', url: 'api/datacollectief/websiteleads/leads',},
        });
        this.Api.websites().then(res => {
            this.websiteFields = res.data.websiteFields;
        }, res => this.$notify((res.data.message || res.data), 'danger'));
    },

    methods: {
        getWebsiteleads() {
            if (!this.wl_options.Website) {
                return;
            }
            this.loading = true;
            this.Api.leads({}, {options: this.wl_options,}).then(res => {
                console.log(res.data);
                this.processed_data = res.data.processed_data;
            }, res => this.$notify((res.data.message || res.data), 'danger'))
                .then(() => this.loading = false);
        },
        getDatacollectiefLink(company) {
            return `https://mijn.datacollectief.nl/?id=${company.external_id}&a=wsl`;
        },
    },

};

Vue.ready(vm);
