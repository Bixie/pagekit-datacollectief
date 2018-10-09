/* global Vue, */

// @vue/component
const vm = {

    el: '#datacollectief-salesviewer',

    name: 'SalesviewerIndex',

    data() {
        const {config,} = window.$data;
        return {
            loading: false,
            sessions: [],
            processed_data: [],
            filter: {
                from: (new Date(config.sv_last_checked)).toISOString(),
                to: (new Date()).toISOString(),
            },
            page: 0,
            config,
        };
    },

    computed: {
        leadsByCompany() {
            const leads = {};
            this.filteredProcessedData.forEach(processed => {
                const {messages, changed_data, company, lead, dc_contacts, known_contacts,} = processed;
                if (!leads[company.id]) {
                    leads[company.id] = {
                        company,
                        messages,
                        changed_data,
                        dc_contacts,
                        known_contacts,
                        leads: [lead,],
                    };
                } else {
                    leads[company.id].leads.push(processed.lead);
                }
            });
            return leads;
        },
        filteredProcessedData() {
            return this.processed_data.map(pd => pd.contactmanager || {}).filter(pd => {
                if (this.filter.status === 'new') {
                    return pd.isNewCompany;
                } else if (this.filter.status === 'matched') {
                    return !pd.isNewCompany;
                } else if (this.filter.status === 'modified') {
                    return pd.changed_data.length > 0;
                }
                return true;
            })
        },
    },

    watch: {
        'page'(page) {
            this.getSessions(page);
        },
    },

    created() {
        this.Api = this.$resource('api/datacollectief', {}, {
            'sessions': {method: 'get', url: 'api/datacollectief/salesviewer/sessions',},
        });
    },

    methods: {
        getSessions(page) {
            this.loading = true;
            this.Api.sessions({}, {filter: {...this.filter, page: (page + 1),},}).then(({data,}) => {
                this.processed_data = data.processed_data;
                this.page = (Number(data.sessions.pagination.current) - 1);
                this.sessions = data.sessions;
            }, res => this.$notify((res.data.message || res.data), 'danger'))
                .then(() => this.loading = false);
        },
        getSiteUrl(url) {
            if (!url.match('^https?://')) {
                return `http://${url}`;
            }
            return url;
        },
    },

};

Vue.ready(vm);