new Vue({
    el: '#SettingsModule',

    data: {
        activeSection: 'business',
        loading: false,
        isEditing: false,
        translations: {
            edit: '',
            create: ''
        },

        allSections: [
            { id: 'business', name: SettingSectionsTranslations.business, icon: 'bi bi-building' },
            // { id: 'business_units', name: SettingSectionsTranslations.business_units, icon: 'bi bi-building-fill-gear' },
            // { id: 'appearance', name: SettingSectionsTranslations.appearance, icon: 'bi bi-palette' }
        ],

        sections: [],

        defaultLogo: DEFAULT_COMPANY_LOGO,

        settings: {
            theme: 'light',
            company: {
                name: '',
                legalName: '',
                taxId: '',
                email: '',
                phone: '',
                address: '',
                is_principal: null,

                country_id: null,
                country_name: '',

                latitude: null,
                longitude: null,

                logo: null,
                logoPreview: null
            },
            tenants: []
        },

        cities: [],

        tenantPrincipalIsPrincipal: false,
        hasData: false
    },

    mounted() {
        const el = ConfigModuleTranslations;
        this.translations.edit = el.edit;
        this.translations.create = el.create;

        this.getTenantPrincipal();

        this.$nextTick(() => {
            this.initMap();
        });
    },

    computed: {
        isPrincipal() {
            return this.tenantPrincipalIsPrincipal;
        }
    },

    watch: {
        tenantPrincipalIsPrincipal: {
            immediate: true,
            handler() {
                this.updateSections();
            }
        },
        hasData: {
            immediate: true,
            handler() {
                this.updateSections();
            }
        }
    },

    methods: {

        updateSections() {

            // business y appearance siempre visibles
            let visibleSections = ['business', 'appearance'];

            // business_units SOLO cuando hay data y es principal
            if (this.hasData && this.isPrincipal) {
                visibleSections.push('business_units');
            }

            this.sections = this.allSections.filter(
                section => visibleSections.includes(section.id)
            );

            // seguridad: sección activa válida
            if (this.sections.length > 0) {
                const activeExists = this.sections.some(
                    section => section.id === this.activeSection
                );

                if (!activeExists) {
                    this.activeSection = this.sections[0].id;
                }
            }
        },

        //get mulsibusine principal
        getTenantPrincipal() {
            axios.post('/tenant-principal')
                .then(res => {
                    const data = res.data;

                    const isEmpty =
                        !data ||
                        (Array.isArray(data) && data.length === 0) ||
                        (typeof data === 'object' && Object.keys(data).length === 0);

                    this.hasData = !isEmpty;

                    this.settings.company = {
                        id: data?.id ?? null,
                        name: data?.name ?? '',
                        legalName: data?.legalName ?? '',
                        taxId: data?.taxId ?? '',
                        email: data?.email ?? '',
                        phone: data?.phone ?? '',
                        address: data?.address ?? '',
                        is_principal: data?.is_principal ?? null,

                        country_id: data?.country_id ?? '',
                        country_name: data?.country_name ?? '',

                        latitude: data?.latitude ?? '',
                        longitude: data?.longitude ?? '',

                        logo: null,
                        logoPreview: data?.logo ?? ''
                    };

                    // es principal SOLO si backend dice que es principal
                    this.tenantPrincipalIsPrincipal = !!data?.is_principal;

                })
                .catch(error => {
                    console.error('Error loading tenant principal:', error);
                });
        },

        //save for section active
        saveSettings(activeSection) {
            switch (activeSection) {
                case 'business':
                case 'business_units':
                    this.saveBusiness();
                    break;

                case 'users':
                    // await this.saveUsers();
                    break;

                default:
                    console.warn('Sección no soportada:', activeSection);
            }
        },

        //Nav section reset
        activesSection(id_section){
            const sectionExists = this.sections.some(
                section => section.id === id_section
            );
            if (!sectionExists) return;

            this.activeSection = id_section;

            switch (this.activeSection) {
                case 'business':
                    this.getTenantPrincipal();
                    this.$nextTick(() => {
                        this.initMap();
                    });
                    break;

                case 'business_units':
                    this.getTenantBusiness();
                    break;
            }
        },

        //Multi busines get
        getTenantBusiness(){
            this.loading = true;
            axios.post('/tenant/all').then(res => {
                this.settings.tenants = res.data;
                this.loading = false;
            });
        },

        //Crear multi busine
        createBussine(){
            this.isEditing = false;
            this.resetForm();
            this.$nextTick(() => this.initMap());
            new bootstrap.Modal('#configModal').show();
        },

        //Edit multi busine
        editBussine(data){
            this.isEditing = true;
            this.resetForm();

            this.settings.company = {
                id: data.id,
                name: data.name,
                legalName: data.legalName,
                taxId: data.taxId,
                email: data.email,
                phone: data.phone,
                address: data.address,
                is_principal: data.is_principal,

                country_id: data.country_id,
                country_name: data.country_name,

                latitude: data.latitude,
                longitude: data.longitude,

                logo: null,
                logoPreview: data.logo
            };

            if (data.is_principal) {
                this.tenantPrincipalIsPrincipal = true;
            }

            this.$nextTick(() => {
                this.initMap();
            });

            new bootstrap.Modal('#configModal').show();
        },

        //create or update busine
        async saveBusiness(){
            const fd = new FormData();

            Object.entries(this.settings.company).forEach(([key, value]) => {
                if (value !== null && value !== '') {
                    fd.append(key, value);
                }
            });

            // if (this.activeSection === 'business' && this.isPrincipal) {
            //     fd.append('is_principal', 1);
            // }

            if (!this.hasData) {
                fd.append('is_principal', 1);
            }
            // SI ya hay data, solo marcar principal si corresponde
            else if (this.activeSection === 'business' && this.isPrincipal) {
                fd.append('is_principal', 1);
            }

            if (this.activeSection === 'business_units' && this.isPrincipal) {
                fd.append('business_units', 1);
            }

            await axios.post('/tenant-principal/save', fd)
                .then(() => {
                    this.getTenantPrincipal();
                    Toast.show({
                        id: 'myToast',
                        title: ConfigModuleTranslations.config_business_title,
                        message: ConfigModuleTranslations.config_business,
                        type: 'success'
                    });
                    location.reload();
                }).catch(error=>{
                    let er = error;
                    if (er.response && er.response.data.errors) {

                        const errors = error.response.data.errors;
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

        //reset form
        resetForm() {
            this.settings.company = {
                id:'',
                name: '',
                legalName: '',
                taxId: '',
                email: '',
                phone: '',
                address: '',

                country_id: null,
                country_name: '',

                latitude: null,
                longitude: null,

                logo: null,
                logoPreview: null
            };
        },

        //mapas
        initMap() {
            const center = {
                lat: this.settings.company.latitude || 19.4326,
                lng: this.settings.company.longitude || -99.1332
            };

            this.map = new google.maps.Map(
                document.getElementById('map'),
                { center, zoom: 14 }
            );

            this.marker = new google.maps.Marker({
                position: center,
                map: this.map,
                draggable: true
            });

            this.map.addListener('click', e =>
                this.setLocation(e.latLng.lat(), e.latLng.lng())
            );

            this.marker.addListener('dragend', e =>
                this.setLocation(e.latLng.lat(), e.latLng.lng())
            );
        },

        setLocation(lat, lng) {
            this.settings.company.latitude = lat;
            this.settings.company.longitude = lng;
            this.marker.setPosition({ lat, lng });
            this.map.panTo({ lat, lng });
        },

        //preview de imagenes
        onLogoChange(e) {
            const file = e.target.files[0];
            if (!file || file.size > 2 * 1024 * 1024) return;

            this.settings.company.logo = file;
            const reader = new FileReader();
            reader.onload = r => this.settings.company.logoPreview = r.target.result;
            reader.readAsDataURL(file);
        },

        //autocomplete countries
        onCountryInput() {
            this.settings.company.country_id = null;

            if (this.settings.company.country_name.length < 2) {
                this.cities = [];
                return;
            }

            axios.get(`/countries/search?q=${this.settings.company.country_name}`)
                .then(res => this.cities = res.data);
        },

        selectCountry(country) {
            this.settings.company.country_id = country.id;
            this.settings.company.country_name = country.name;
            this.cities = [];
        },

        validateCountry() {
            if (!this.settings.company.country_id) {
                this.settings.company.country_name = '';
                Toast.show({
                    id: 'myToast',
                    title: "",
                    message: ConfigModuleTranslations.country_select,
                    type: 'warning'
                });
            }
        },

        //Sin uso
        loadSettings() {
            const saved = localStorage.getItem('company_settings');
            if (saved) this.settings = JSON.parse(saved);
        },
    }
});
