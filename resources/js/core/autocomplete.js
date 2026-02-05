Vue.component('autocomplete', {
    template: `
        <div class="position-relative">
            <label v-if="label" class="form-label">{{ label }}</label>

            <input
                type="text"
                class="form-control"
                :placeholder="placeholder"
                v-model="search"
                @input="onInput"
                @blur="onBlur"
            >

            <ul v-if="items.length"
                class="list-group position-absolute w-100"
                style="z-index:1000; max-height:200px; overflow:auto;">
                <li
                    v-for="item in items"
                    :key="item.id"
                    class="list-group-item list-group-item-action"
                    @mousedown.prevent="select(item)"
                    style="cursor:pointer">
                    {{ item.name }}
                </li>
            </ul>
        </div>
    `,

    props: {
        value: {
            type: Object,
            default() {
                return { id: null, name: '' }
            }
        },

        label: String,

        placeholder: {
            type: String,
            default: 'Escribe...'
        },

        url: {
            type: String,
            required: true
        },

        extraParams: {
            type: Object,
            default() {
                return {}
            }
        },

        minChars: {
            type: Number,
            default: 2
        }
    },

    data() {
        return {
            search: this.value.name,
            items: []
        }
    },

    watch: {
        value(val) {
            this.search = val.name
        }
    },

    methods: {
        onInput() {
            this.$emit('input', {
                id: null,
                name: this.search
            })

            if (this.search.length < this.minChars) {
                this.items = []
                return
            }

            axios.get(this.url, {
                params: Object.assign(
                    { q: this.search },
                    this.extraParams
                )
            }).then(res => {
                this.items = res.data
            })
        },

        select(item) {
            this.search = item.name
            this.items = []

            this.$emit('input', {
                id: item.id,
                name: item.name
            })
        },

        onBlur() {
            setTimeout(() => {
                if (!this.value.id) {
                    this.search = ''
                    this.$emit('input', { id: null, name: '' })
                }
            }, 150)
        }
    }
})
