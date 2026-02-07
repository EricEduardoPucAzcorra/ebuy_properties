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
            status_property_id: 2, //pendiente
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
        },
        errors: {},
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

            if (!this.validateForm()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Por favor completa los campos marcados'
                });
                return;
            }

            const formData = new FormData();

            formData.append('title', this.propertyForm.title);
            formData.append('description', this.propertyForm.description);
            formData.append('type_property_id', this.propertyForm.type_property_id);
            formData.append('type_operation_id', this.propertyForm.type_operation_id);
            formData.append('status_property_id', 3);
            formData.append('price', this.propertyForm.price);
            formData.append('currency', this.propertyForm.currency);

            formData.append('address[street]', this.propertyForm.address.street);
            formData.append('address[number]', this.propertyForm.address.number);
            formData.append('address[neighborhood]', this.propertyForm.address.neighborhood);
            formData.append('address[address]', this.propertyForm.address.address);
            formData.append('address[postal_code]', this.propertyForm.address.postal_code);
            formData.append('address[country_id]', this.propertyForm.address.country.id);
            formData.append('address[state_id]', this.propertyForm.address.state.id);
            formData.append('address[city_id]', this.propertyForm.address.city.id);
            formData.append('address[latitude]', this.propertyForm.address.location.lat);
            formData.append('address[longitude]', this.propertyForm.address.location.lng);
            formData.append('address[references]', this.propertyForm.address.references);

            this.propertyForm.features.forEach((feature_id, i) => {
                formData.append(`features[${i}]`, feature_id);
            });

            this.propertyForm.attributes.forEach((attr, i) => {
                formData.append(`attributes[${i}][key]`, attr.key);
                formData.append(`attributes[${i}][value]`, attr.value);
            });

            this.propertyForm.media.files.forEach((f, i) => {
                formData.append(`media[${i}][file]`, f.file);
                formData.append(`media[${i}][is_primary]`, f.isPrimary ? 1 : 0);
                formData.append(`media[${i}][type]`, f.type);
            });

            axios.post('/save/mypropertie', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(res => {
                alert('¡Propiedad guardada correctamente!');
                this.showForm = false;
                this.fetchProperties(); // refresca lista
            })
            .catch(err => {
                let er = err;

                if (er.response && er.response.data.errors) {

                    const errors = err.response.data.errors;
                    let errorHtml = '<ul style="text-align:left;">';

                    Object.keys(errors).forEach(field => {
                        errors[field].forEach(msg => {
                            errorHtml += `<li>${msg}</li>`;
                        });
                    });

                    errorHtml += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: window.erros_validation,
                        html: errorHtml
                    });

                }
            });
        },
        editProperty(id) {
            // window.location.href = `/properties/${id}/edit`;
        },

        previewProperty(id) {
            // window.open(`/properties/${id}`, '_blank');
        },

        toggleStatus(property) {
            // // aquí llamas a tu API
            // property.status = property.status === 'Activo'
            //     ? 'Inactivo'
            //     : 'Activo';
        },

        getAttribute(property, key) {
            return property.attributes?.find(a => a.key === key)?.value ?? null
        },

        getStatusBadge(name) {
            const map = {
                'Publicado': 'bg-success',
                'Pendiente': 'bg-warning text-dark',
                'No publicado': 'bg-secondary',
                'Cancelado': 'bg-danger',
                'Pausado': 'bg-info text-dark',
                'Vendido': 'bg-dark',
                'Rentado': 'bg-primary',
            };

            return map[name] ?? 'bg-light text-dark';
        },

        validateForm() {
            this.errors = {};

            if (!this.propertyForm.title.trim()) {
                this.errors.title = 'El título es obligatorio';
            }

            if (!this.propertyForm.description.trim()) {
                this.errors.description = 'La descripción es obligatoria';
            }

            if (!this.propertyForm.type_property_id) {
                this.errors.type_property_id = 'Selecciona un tipo de propiedad';
            }

            if (!this.propertyForm.type_operation_id) {
                this.errors.type_operation_id = 'Selecciona un tipo de operación';
            }

            if (!this.propertyForm.price || this.propertyForm.price <= 0) {
                this.errors.price = 'Ingresa un precio válido';
            }

            if (!this.propertyForm.address.street.trim()) {
                this.errors.street = 'La calle es obligatoria';
            }

            // if (!this.propertyForm.address.city.id) {
            //     this.errors.city = 'Selecciona una ciudad';
            // }

            return Object.keys(this.errors).length === 0;
        },

    }
})

