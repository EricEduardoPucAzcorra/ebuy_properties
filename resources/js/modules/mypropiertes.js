import './../core/autocomplete';
import './../core/map-selector';
import './../core/media-uploader';

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
        features: [],
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
                location:{
                    lat: 19.4326,
                    lng: -99.1332
                },
                references: ''
            },
            features: [],
            attributes: [
            ],
            media: {
                files: []
            }
        },
        newAttribute: {
            key: '',
            value: ''
        }
    },

    mounted() {
        this.fetchTypeOperations();
        this.fetchTypeOProperties();
        this.loadFeatures();
        this.loadDefaultAttributes();
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
        loadFeatures() {
            axios.get('/property-features')
                .then(res => {
                    this.features = res.data;
                });
        },
        toggleFeature(id) {
            const index = this.propertyForm.features.indexOf(id);

            if (index === -1) {
                this.propertyForm.features.push(id);
            } else {
                this.propertyForm.features.splice(index, 1);
            }
        },
        loadDefaultAttributes() {
            axios.get('/property-attributes/defaults')
                .then(res => {
                    this.defaultAttributes = res.data;
                    res.data.forEach(attr => {
                        this.propertyForm.attributes.push({
                            key: attr.key,
                            value: ''
                        });
                    });
                });
        },
        addCustomAttribute() {
            if (!this.newAttribute.key || !this.newAttribute.value) return;

            this.propertyForm.attributes.push({
                key: this.newAttribute.key,
                value: this.newAttribute.value
            });

            this.newAttribute.key = '';
            this.newAttribute.value = '';
        },
        submitForm() {
            const formData = new FormData();

            // formData.append('title', this.propertyForm.title);

            // this.propertyForm.media.files.forEach((f, i) => {
            //     formData.append(`media[${i}]`, f.file);
            //     formData.append(`media_is_primary[${i}]`, f.isPrimary ? 1 : 0);
            //     formData.append(`media_type[${i}]`, f.type);
            // });

            // axios.post('/properties', formData, {
            //     headers: { 'Content-Type': 'multipart/form-data' }
            // }).then(res => {
            //     console.log('Guardado!');
            // });
            alert('subiendo contenido')
        }
    }
})

