import './../core/autocomplete';

new Vue({
    el: '#mypropiertes',

    data: {
        type_operations: [],
        type_properties:[],
        properties: [],
        pagination: {},
        loading: true,
        activeTab: null,
        showForm: false,
        activeCreateTab: 'form',
        cities:[],
        propertyForm:{
            title: '',
            description: '',
            type_property_id: null,
            type_operation_id: null,
            status_property_id: null,
            price: 0.0,
            currency: 'MNX',
            address:{
                street: '',
                number: '',
                neighborhood: '',
                address: '',
                postal_code: '',
                country: { id: null, name: '' },
                state:   { id: null, name: '' },
                city:    { id: null, name: '' },
                latitude: '',
                longitude: '',
                references: ''
            }
        }
    },

    mounted() {
        this.fetchTypeOperations();
        this.fetchTypeOProperties();
    },

    watch: {
        'propertyForm.address.country.id'() {
            this.propertyForm.address.state = { id: null, name: '' }
            this.propertyForm.address.city  = { id: null, name: '' }
        },

        'propertyForm.address.state.id'() {
            this.propertyForm.address.city = { id: null, name: '' }
        }
    },

    methods: {
        changeTab(tab) {
            this.activeTab = tab
            this.fetchProperties();
        },

        fetchProperties(page = 1) {
            this.loading = true

            axios.get('/owner/my-properties', {
                params: {
                    page: page,
                    type_operation_id: this.activeTab
                }
            })
            .then(res => {
                this.properties = res.data.data
                this.pagination = res.data
            })
            .finally(() => {
                this.loading = false
            })
        },

        fetchTypeOperations(){
            let self = this;
            axios.get('/types_operations').then(function(response){
                self.type_operations = response.data;
                if (self.type_operations.length > 0) {
                    self.activeTab = self.type_operations[0].id
                    self.fetchProperties(1)
                }
            });
        },

        fetchTypeOProperties(){
            let self = this;
            axios.get('/types_properties').then(function(response){
                self.type_properties = response.data;
            });
        },

        //Automcompletes

        onCountryInput() {
            this.propertyForm.address.country_id = null;

            if (this.propertyForm.address.country_name.length < 2) {
                this.cities = [];
                return;
            }

            axios.get(`/countries/search?q=${this.propertyForm.address.country_name}`)
                .then(res => this.cities = res.data);
        },

        selectCountry(country) {
            this.propertyForm.address.country_id = country.id;
            this.propertyForm.address.country_name = country.name;
            this.cities = [];
        },

        validateCountry() {
            if (!this.propertyForm.address.country_id) {
                this.propertyForm.address.country_name = '';
                // Toast.show({
                //     id: 'myToast',
                //     title: "",
                //     message: ConfigModuleTranslations.country_select,
                //     type: 'warning'
                // });
                alert('Ingresa texto')
            }
        },
    }
})

