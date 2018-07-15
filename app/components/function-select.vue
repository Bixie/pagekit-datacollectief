<template>

    <div>
        <div class="uk-grid uk-grid-small">
            <div v-for="code in selected" class="uk-width-1-4">
                <div class="uk-badge uk-flex uk-flex-middle uk-margin-small-bottom"
                     track-by="$index">
                    <span class="uk-flex-item-1">{{ getLabel(code) }} </span>
                    <a @click="remove(code)" class="uk-close uk-margin-small-left"></a>
                </div>
            </div>
        </div>

        <div class="uk-flex uk-flex-middle">
            <select v-model="new_item" class="uk-flex-item-1 uk-margin-small-right">
                <option value="">{{ 'Please select' | trans }}</option>
                <option v-for="item in options" :value="item.FunctionID">{{ item.Description }}</option>
            </select>
            <button type="button" class="uk-button uk-button-small" @click="add">{{ 'Add' | trans }}</button>
        </div>

    </div>

</template>
<script>
/* global _ */

export default {

    name: 'FunctionSelect',

    props: {
        items: {},
        value: [],
    },

    data: () => ({
        new_item: '',
    }),

    computed: {
        selected: {
            get() {
                return JSON.parse(JSON.stringify(this.value));
            },
            set(value) {
                this.$emit('input:functions', value);
            },
        },
        options() {
            return _.sortBy(_.values(this.items), 'Description')
        },
    },

    methods: {
        add() {
            if (this.new_item && this.selected.indexOf(this.new_item) === -1) {
                this.selected.push(this.new_item);
                this.$emit('input:functions', this.selected);
            }
        },
        remove(code) {
            let index = this.selected.indexOf(code);
            if (index > -1) {
                this.selected.splice(index, 1);
                this.$emit('input:functions', this.selected);
            }
        },
        getLabel(code) {
            return this.items[code] ? this.items[code].Description : code;
        },
    },

}

</script>