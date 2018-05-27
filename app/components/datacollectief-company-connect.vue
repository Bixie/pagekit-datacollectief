<template>

    <div>
        <div class="uk-flex uk-flex-middle">
            <label class="uk-form-label uk-flex-item-1">ID</label>
            <input v-model="externalId" type="text" class="uk-form-width-small">
        </div>
        <div class="uk-margin-small-top uk-text-small uk-text-right">
            <a :href="dataCollectiefUrl" target="_blank">
                <i class="uk-icon-external-link uk-margin-small-right"></i>
                Data collectief website
            </a>
        </div>
    </div>

</template>
<script>

    export default {

        name: 'DatacollectiefCompanyConnect',

        props: {
            external_id: String,
            company: Object,
        },

        computed: {
            externalId: {
                get() {
                    return this.external_id;
                },
                set(value) {
                    this.$emit('input:external_id', value);
                },
            },
            dataCollectiefUrl() {
                return `https://mijn.datacollectief.nl/?id=${this.externalId}&a=wsl`;
            },
        },

    }

</script>