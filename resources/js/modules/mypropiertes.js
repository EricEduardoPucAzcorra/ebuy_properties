import './../core/autocomplete_location';
import './../core/map-selector';
import './../core/media-uploader';
import './../directives/auto_trans'

new Vue({
    el: '#mypropiertes',

    data: {
        type_operations: [],
        type_properties: [],
        properties: [],
        pagination: {},
        loading: true,
        isSubmitting: false,
        activeTab: null,
        showForm: false,
        activeCreateTab: 'form',
        activeFormTab: 'info',
        // features: [],
        featureCategories: [],
        updatingFromMap: false,
        propertyForm: {
            cadastral_code: '',
            title: '',
            description: '',
            type_property_id: null,
            type_operation_id: null,
            status_property_id: 2, //pendiente
            price: 0.0,
            price_negotiable: 'NO',
            currency: 'MNX',
            address: {
                street: '',
                number: '',
                neighborhood: '',
                address: '',
                postal_code: '',
                country: { id: null, name: '' },
                state: { id: null, name: '' },
                city: { id: null, name: '' },
                location: {
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
            },
            contacts: [],
        },
        newAttribute: {
            key: '',
            value: ''
        },
        errors: {},
        newContact: {
            name: '',
            phone: '',
            whatsapp: '',
            email: '',
            date_atention: '',
            photo: null
        },

        ///edicion
        isEdit: false,
        editId: null,

        //states
        showStateModal: false,
        states: [],
        selectedProperty: null,
        selectedState: null,
    },

    mounted() {
        this.fetchTypeOperations();
        this.fetchTypeOProperties();
        this.loadFeatures();
        this.loadDefaultAttributes();
    },

    watch: {
        'propertyForm.address.country'(val) {
            // Solo actualiza si no es edición y no estamos actualizando desde el mapa
            if (!this.isEdit && !this.updatingFromMap && val?.lat && val?.lng) {
                this.propertyForm.address.location = { lat: val.lat, lng: val.lng };
            }
        },
        'propertyForm.address.state'(val) {
            if (!this.isEdit && !this.updatingFromMap && val?.lat && val?.lng) {
                this.propertyForm.address.location = { lat: val.lat, lng: val.lng };
            }
        },
        'propertyForm.address.city'(val) {
            if (!this.isEdit && !this.updatingFromMap && val?.lat && val?.lng) {
                this.propertyForm.address.location = { lat: val.lat, lng: val.lng };
            }
        },
        propertyForm: {
            deep: true,
            handler(newVal) {

                Object.keys(this.errors).forEach(key => {

                    if (newVal[key] !== undefined && newVal[key]) {
                        this.$delete(this.errors, key);
                    }

                    if (newVal.address && newVal.address[key]) {
                        this.$delete(this.errors, key);
                    }
                });

                if (this.errors.features && newVal.features.length > 0) {
                    this.$delete(this.errors, 'features');
                }

                if (this.errors.media && newVal.media.files.length > 0) {
                    this.$delete(this.errors, 'media');
                }
            }
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

        fetchTypeOperations() {
            let self = this;
            axios.get('/types_operations').then(function (response) {
                self.type_operations = response.data;
                if (self.type_operations.length > 0) {
                    self.activeTab = self.type_operations[0].id
                    self.fetchProperties(1)
                }
            });
        },

        fetchTypeOProperties() {
            let self = this;
            axios.get('/types_properties').then(function (response) {
                self.type_properties = response.data;
            });
        },

        loadFeatures() {
            axios.get('/property-features')
                .then(res => {
                    this.featureCategories = res.data;
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

        addContact() {
            if (!this.newContact.name || !this.newContact.phone) return;

            this.propertyForm.contacts.push({
                name: this.newContact.name,
                phone: this.newContact.phone,
                whatsapp: this.newContact.whatsapp,
                email: this.newContact.email,
                date_atention: this.newContact.date_atention,
                photo: this.newContact.photo
            });

            this.newContact = {
                name: '',
                phone: '',
                whatsapp: '',
                email: '',
                date_atention: '',
                photo: null
            };
        },

        removeContact(index) {
            this.propertyForm.contacts.splice(index, 1);
        },

        onContactPhotoChange(e, index) {
            const file = e.target.files[0];
            if (file) {
                this.propertyForm.contacts[index].photo = file;
            }
        },

        async submitForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const isValid = this.validateForm();

            if (!isValid) {
                this.isSubmitting = false;

                const textosParaTraducir = [
                    'Datos incompletos',
                    'Por favor completa los campos marcados',
                    ...Object.values(this.errors)
                ];

                await window.auto_trans_batch(textosParaTraducir);

                let erroresTraducidos = {};
                Object.keys(this.errors).forEach(key => {
                    const texto = this.errors[key];
                    erroresTraducidos[key] = window.cacheTraducciones[texto] || texto;
                });

                this.errors = erroresTraducidos;

                Swal.fire({
                    icon: 'warning',
                    title: window.cacheTraducciones['Datos incompletos'] || 'Datos incompletos',
                    text: window.cacheTraducciones['Por favor completa los campos marcados'] || 'Error'
                });
                return;
            }

            const formData = new FormData();

            formData.append('cadastral_code', this.propertyForm.cadastral_code);
            formData.append('title', this.propertyForm.title);
            formData.append('description', this.propertyForm.description);
            formData.append('type_property_id', this.propertyForm.type_property_id);
            formData.append('type_operation_id', this.propertyForm.type_operation_id);
            formData.append('status_property_id', 3);
            formData.append('price', this.propertyForm.price);
            formData.append('price_negotiable', this.propertyForm.price_negotiable);
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

            this.$delete(this.errors, 'media');

            this.propertyForm.media.files.forEach((f, i) => {
                if (f.existing) {
                    // Para imágenes que ya existen en el servidor
                    formData.append(`keep_media[${i}][path]`, f.path);
                    formData.append(`keep_media[${i}][is_primary]`, f.isPrimary ? 1 : 0);
                    formData.append(`keep_media[${i}][type]`, f.type);
                } else if (f.file) {
                    // Para archivos nuevos
                    formData.append(`media[${i}][file]`, f.file);
                    formData.append(`media[${i}][is_primary]`, f.isPrimary ? 1 : 0);
                    formData.append(`media[${i}][type]`, f.type);
                }
            });

            this.propertyForm.contacts.forEach((contact, i) => {
                formData.append(`contacts[${i}][name]`, contact.name);
                formData.append(`contacts[${i}][phone]`, contact.phone);
                formData.append(`contacts[${i}][whatsapp]`, contact.whatsapp ?? '');
                formData.append(`contacts[${i}][email]`, contact.email ?? '');
                // formData.append(`contacts[${i}][date_atention]`, contact.date_atention ?? '');

                if (contact.photo instanceof File) {
                    formData.append(`contacts[${i}][photo]`, contact.photo);
                }

            });

            const url = this.isEdit
                ? `/update/mypropertie/${this.editId}`
                : '/save/mypropertie';

            axios.post(url, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(res => {
                    // alert('¡Propiedad guardada correctamente!');

                    Swal.fire({
                        icon: 'success',
                        title: this.isEdit
                            ? 'Propiedad actualizada'
                            : 'Propiedad guardada'
                    });

                    this.showForm = false;
                    this.resetForm();
                    this.fetchProperties();
                    this.isEdit = false;
                    this.editId = null;
                })
                .catch(err => {
                    this.isSubmitting = false;
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
                }).finally(() => {
                    this.isSubmitting = false;
                });
        },

        editProperty(property) {
            this.showForm = true;
            this.isEdit = true;
            this.editId = property.id;
            this.propertyForm.address.location = [];

            let existingAttributes = property.attributes ?? [];

            let finalAttributes = [];

            this.defaultAttributes.forEach(def => {
                const found = existingAttributes.find(a => a.key === def.key);
                finalAttributes.push({
                    key: def.key,
                    value: found ? found.value : ''
                });
            });

            existingAttributes.forEach(attr => {
                const isDefault = this.defaultAttributes.some(def => def.key === attr.key);
                if (!isDefault) {
                    finalAttributes.push(attr);
                }
            });

            this.propertyForm = {
                cadastral_code: property.cadastral_code ?? '',
                title: property.title ?? '',
                description: property.description ?? '',
                type_property_id: property.type_property_id ?? null,
                type_operation_id: property.type_operation_id ?? null,
                status_property_id: property.status_property_id ?? 2,
                price: Number(property.price.replace(',', '')),
                price_negotiable: property.price_negotiable ? 'SI' : 'NO',
                currency: property.currency ?? 'MNX',

                address: {
                    street: property.address.street ?? '',
                    number: property.address.number ?? '',
                    neighborhood: property.address.neighborhood ?? '',
                    address: property.address.address ?? '',
                    postal_code: property.address.postal_code ?? '',
                    country: {
                        id: property.address.country.id,
                        name: property.address.country.name,
                        lat: property.address.country.lat,
                        lng: property.address.country.lng
                    },
                    state: {
                        id: property.address.state.id,
                        name: property.address.state.name,
                        lat: property.address.state.lat,
                        lng: property.address.state.lng
                    },
                    city: {
                        id: property.address.city.id,
                        name: property.address.city.name,
                        lat: property.address.city.lat,
                        lng: property.address.city.lng
                    },
                    location: {
                        lat: property.address.location.latitude ?? 19.4326,
                        lng: property.address.location.longitude ?? -99.1332
                    },
                    references: property.address.references ?? ''
                },

                features: property.features ?? [],

                attributes: finalAttributes,

                media: {
                    files: [
                        ...(property.images?.main ? [{
                            file: null,
                            preview: property.images.main,
                            path: property.images.main_path || this.getRelativePath(property.images.main),
                            isPrimary: true,
                            existing: true,
                            type: 'image'
                        }] : []),

                        ...(property.images?.others || []).map(img => ({
                            file: null,
                            preview: img.url || img,
                            path: img.path || this.getRelativePath(img.url || img),
                            isPrimary: false,
                            existing: true,
                            type: 'image'
                        }))
                    ]
                },
                contacts: (property.contacts || []).map(c => ({
                    id: c.id ?? null,
                    name: c.name ?? '',
                    phone: c.phone ?? '',
                    whatsapp: c.whatsapp ?? '',
                    email: c.email ?? '',
                    photo: null,
                }))

            };
        },

        openStateModal(property) {
            console.log(property)
            this.selectedProperty = property;
            this.selectedState = property.status_property_id ?? null;

            if (this.states.length === 0) {
                axios.get('/states-properties')
                    .then(res => this.states = res.data);
            }

            const modal = new bootstrap.Modal(
                document.getElementById('changeStatusModal')
            );
            modal.show();
        },

        saveStatus() {
            axios.post(`/properties/${this.selectedProperty.id}/status`, {
                status_id: this.selectedState
            }).then(() => {
                this.resetForm();
                this.fetchProperties();
                bootstrap.Modal.getInstance(
                    document.getElementById('changeStatusModal')
                ).hide();
            });
        },

        previewProperty(id) {
            // window.open(`/properties/${id}`, '_blank');
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
            let newErrors = {};

            // if (!this.propertyForm.cadastral_code.trim()) newErrors.cadastral_code = 'Ingresa el numero catastral';
            if (!this.propertyForm.title.trim()) newErrors.title = 'Ingrese el titulo';
            // if (!this.propertyForm.description.trim()) newErrors.description = 'Ingrese una descripción';
            if (!this.propertyForm.type_property_id) newErrors.type_property_id = 'Selecciona un tipo de propiedad';
            if (!this.propertyForm.type_operation_id) newErrors.type_operation_id = 'Selecciona un tipo de operación';

            if (!this.propertyForm.address.street.trim()) newErrors.street = 'Ingrese la calle';
            if (!this.propertyForm.address.number.trim()) newErrors.number = 'Ingrese el numero';
            if (!this.propertyForm.address.postal_code.trim()) newErrors.postal_code = 'Ingrese el codigo postal';
            if (!this.propertyForm.address.country.id) newErrors.country = 'Seleciona un pais';
            if (!this.propertyForm.address.state.id) newErrors.state = 'Seleciona un estado';
            if (!this.propertyForm.address.city.id) newErrors.city = 'Selecciona una ciudad';

            if (!this.propertyForm.price || this.propertyForm.price <= 0) newErrors.price = 'Ingresa un precio válido';
            if (this.propertyForm.features.length === 0) newErrors.features = 'Debes seleccionar al menos una característica';
            if (this.propertyForm.media.files.length === 0) newErrors.media = 'Debes subir al menos una imagen';

            this.errors = newErrors;
            return Object.keys(this.errors).length === 0;
        },

        resetForm() {
            this.propertyForm = this.getInitialPropertyForm();

            this.newAttribute = {
                key: '',
                value: ''
            };

            this.newContact = {
                name: '',
                phone: '',
                whatsapp: '',
                email: '',
                date_atention: '',
                photo: null
            };

            this.errors = {};

            this.loadDefaultAttributes();
        },

        getInitialPropertyForm() {
            return {
                cadastral_code: '',
                title: '',
                description: '',
                type_property_id: null,
                type_operation_id: null,
                status_property_id: 2,
                price: 0.0,
                price_negotiable: 'NO',
                currency: 'MNX',
                address: {
                    street: '',
                    number: '',
                    neighborhood: '',
                    address: '',
                    postal_code: '',
                    country: { id: null, name: '' },
                    state: { id: null, name: '' },
                    city: { id: null, name: '' },
                    location: {
                        lat: 19.4326,
                        lng: -99.1332
                    },
                    references: ''
                },
                features: [],
                attributes: [],
                media: {
                    files: []
                },
                contacts: []
            };
        },

        getRelativePath(url) {
            if (typeof url !== 'string') return url;
            if (url.includes('/storage/')) {
                return url.split('/storage/')[1];
            }
            return url;
        },

        async handleLocationSelection(location) {
            try {
                this.updatingFromMap = true;

                const response = await fetch(`/location/reverse-geocode?lat=${location.lat}&lng=${location.lng}`);
                const data = await response.json();

                if (data.error) {
                    console.error('Error en reverse geocoding:', data.error);
                    return;
                }

                if (data.country) {
                    this.propertyForm.address.country = {
                        id: data.country.id,
                        name: data.country.name,
                        lat: data.country.lat,
                        lng: data.country.lng
                    };
                }

                if (data.state) {
                    this.propertyForm.address.state = {
                        id: data.state.id,
                        name: data.state.name,
                        lat: data.state.lat,
                        lng: data.state.lng
                    };
                }

                if (data.city) {
                    this.propertyForm.address.city = {
                        id: data.city.id,
                        name: data.city.name,
                        lat: data.city.lat,
                        lng: data.city.lng
                    };
                }

                this.$nextTick(() => {
                    console.log('Ubicación actualizada:', {
                        country: this.propertyForm.address.country?.name,
                        state: this.propertyForm.address.state?.name,
                        city: this.propertyForm.address.city?.name
                    });

                    setTimeout(() => {
                        this.updatingFromMap = false;
                    }, 100);
                });

            } catch (error) {
                console.error('Error al obtener la ubicación:', error);
                this.updatingFromMap = false;
            }
        },
    }
})

