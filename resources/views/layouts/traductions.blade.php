<script>
    //Datatables
    window.datatableLanguage = @json(__('messages.datatables'));
    //Errors and general messages
    window.error_system = @json(__('messages.error_system'));
    window.active = @json(__('general.active'));
    window.inactive = @json(__('general.inactive'));
    window.yes = @json(__('general.yes'));
    window.no = @json(__('general.no'));
    window.now = @json(__('general.now'));
    window.erros_validation = @json(__('general.erros_validation'));
    window.yes_continue = @json(__('general.yes_continue'));
    window.cancel = @json(__('general.cancel'));

    //Module Translations
    window.UsersModuleTranslations = {
        edit: @json(__('general.users.edit')),
        create: @json(__('general.users.create')),
        are_you_sure_activate_user_title: @json(__('general.users.messages.are_you_sure_status_title')),
        are_you_sure_activate_user_info: @json(__('general.users.messages.are_you_sure_status_info')),
        user_updated_success: @json(__('general.users.messages.user_updated_success')),
        user_created_success: @json(__('general.users.messages.user_created_success')),
    };

    window.RolesModuleTranslations = {
        edit: @json(__('general.roles.edit')),
        create: @json(__('general.roles.create')),
        are_you_sure_activate_user_title: @json(__('general.roles.messages.are_you_sure_status_title')),
        are_you_sure_activate_user_info: @json(__('general.roles.messages.are_you_sure_status_info')),
        role_updated_success: @json(__('general.roles.messages.role_updated_success')),
        role_created_success: @json(__('general.roles.messages.role_created_success')),
        role_permission_updated:  @json(__('general.roles.messages.save_permissions')),
        role_permission_updated_title:  @json(__('general.roles.messages.save_permissions_title')),

    };

    window.SettingSectionsTranslations = {
        business: @json(__('general.settings.business')),
        business_units: @json(__('general.settings.business_units')),
        general: @json(__('general.settings.general')),
        appearance: @json(__('general.settings.appearance')),
        notifications: @json(__('general.settings.notifications'))
    };

    window.ConfigModuleTranslations = {
        create: @json(__('general.settings.create.busine')),
        edit: @json(__('general.settings.edit.busine')),
        setting_busine_created_success: @json(__('general.settings.messages.setting_busine_created_success')),
        setting_busine_updated_success: @json(__('general.settings.messages.setting_busine_updated_success')),
        config_business_title: @json(__('general.settings.messages.config_business_title')),
        config_business: @json(__('general.settings.messages.config_business')),
        country_select:  @json(__('general.settings.business.country_select')),
    };

    window.ToastTranslations = {
        success: @json(__('messages.success')),
        mistake: @json(__('messages.mistake')),
        warning: @json(__('messages.warning')),
        information: @json(__('messages.information'))
    };

    window.ProfileTranslations = {
        updated_profile: @json(__('general.profile.message.updated_profile')),
        updated_profile_success: @json(__('general.profile.message.updated_profile_success')),
        change_password: @json(__('general.profile.message.change_password')),
        updated_you_profile: @json(__('general.profile.message.updated_you_profile')),
        update_profile_logout: @json(__('general.profile.message.update_profile_logout')),
        update_profile_continue: @json(__('general.profile.message.update_profile_continue')),
        update_profile_session_close:  @json(__('general.profile.message.update_profile_session_close')),
    };

    window.PlanTranslations = {
        create: @json(__('general.plans.create')),
        edit: @json(__('general.plans.edit')),
        plan_created_success: @json(__('general.plans.messages.plan_created_success')),
        plan_updated_success: @json(__('general.plans.messages.plan_updated_success')),
    };

    window.PlanFeaturesModuleTranslations = {
        edit: @json(__('general.plan_feature.edit')),
        create: @json(__('general.plan_feature.create')),
        change_status_title: @json(__('general.plan_feature.messages.change_status_title')),
        change_status_info: @json(__('general.plan_feature.messages.change_status_info')),
        feature_created_success: @json(__('general.plan_feature.messages.feature_created_success')),
        feature_updated_success: @json(__('general.plan_feature.messages.feature_updated_success')),
    };

    window.auto_trans = (text)=>{
        console.log(text)
    };

</script>
