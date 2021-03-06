<?php

function awpcp_modules_manager() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_ModulesManager( awpcp(), awpcp_licenses_manager(), awpcp_modules_updater(), awpcp()->settings );
    }

    return $instance;
}

class AWPCP_ModulesManager {

    private $plugin;
    private $licenses_manager;
    private $modules_updater;
    private $settings;

    private $modules = array();
    private $notices = array();

    public function __construct( $plugin, $licenses_manager, $modules_updater, $settings ) {
        $this->plugin = $plugin;
        $this->licenses_manager = $licenses_manager;
        $this->modules_updater = $modules_updater;
        $this->settings = $settings;
    }

    public function load_modules() {
        do_action( 'awpcp-load-modules', $this );
    }

    public function load( $module ) {
        $this->modules[ $module->slug ] = $module;

        try {
            $this->load_module( $module );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }
    }

    private function load_module( $module ) {
        $module->load_textdomain();
        $this->verify_version_compatibility( $module );

        if ( $this->is_premium_module( $module ) ) {
            $this->settings->add_license_setting( $module->name, $module->slug );
            $this->verify_license_status( $module );
        }

        $this->handle_module_updates( $module );
        $module->setup( $this->plugin );
    }

    private function verify_version_compatibility( $module ) {
        $modules = $this->plugin->get_premium_modules_information();

        if ( ! isset( $modules[ $module->slug ] ) ) {
            $this->notices['modules-not-registered'][] = $module;
            throw new AWPCP_Exception( 'Module is not registered.' );
        }

        if ( version_compare( $this->plugin->version, $module->required_awpcp_version, '<' ) ) {
            $this->notices['modules-that-require-different-awpcp-version'][] = $module;
            throw new AWPCP_Exception( 'Required AWPCP version not installed.' );
        }

        if ( ! $this->plugin->is_compatible_with( $module->slug, $module->version ) ) {
            $this->notices['modules-not-compatible'][] = $module;
            throw new AWPCP_Exception( 'Module not compatible with installed AWPCP version.' );
        }
    }

    protected function is_premium_module( $module ) {
        $free_modules = array( 'xml-sitemap' );

        if ( in_array( $module->slug, $free_modules ) ) {
            return false;
        }

        $hidden_modules = array( 'videos', 'google-checkout' );

        if ( in_array( $module->slug, $hidden_modules ) ) {
            return false;
        }

        return true;
    }

    private function verify_license_status( $module ) {
        if ( $this->licenses_manager->is_license_inactive( $module->name, $module->slug ) ) {
            $module->notices[] = 'inactive-license-notice';
            $this->notices['modules-with-inactive-license'][] = $module;
            throw new AWPCP_Exception( "Module's license is inactive." );
        } else if ( ! $this->module_has_an_accepted_license( $module ) ) {
            $this->notices['modules-with-invalid-license'][] = $module;
            throw new AWPCP_Exception( 'Module has not valid license.' );
        }

        if ( $this->licenses_manager->is_license_expired( $module->name, $module->slug ) ) {
            $this->notices['modules-with-expired-license'][] = $module;
        }
    }

    private function module_has_an_accepted_license( $module ) {
        if ( $this->licenses_manager->is_license_valid( $module->name, $module->slug ) ) {
            return true;
        }

        if ( $this->licenses_manager->is_license_expired( $module->name, $module->slug ) ) {
            return true;
        }

        return false;
    }

    private function handle_module_updates( $module ) {
        // TODO: maybe we don't need to pass the license.
        // Maybe we can have the Modules Updater fetch it when necessary.
        $license = $this->licenses_manager->get_module_license( $module->slug );
        $this->modules_updater->watch( $module, $license );
    }

    public function show_admin_notices() {
        if ( ! awpcp_current_user_is_admin() ) {
            return;
        }

        foreach ( $this->notices as $notice => $modules ) {
            $this->show_admin_notice( $notice, $modules );
        }
    }

    private function show_admin_notice( $notice, $modules ) {
        switch ( $notice ) {
            case 'modules-not-registered':
                echo $this->show_modules_no_registered_notice( $modules );
                break;
            case 'modules-that-require-different-awpcp-version':
                echo $this->show_required_awpcp_version_notice( $modules );
                break;
            case 'modules-not-compatible':
                return $this->show_modules_not_compatible_notice( $modules );
                break;
            case 'modules-with-inactive-license':
                echo $this->show_inactive_licenses_notice( $modules );
                break;
            case 'modules-with-invalid-license':
                echo $this->show_invalid_licenses_notice( $modules );
                break;
            case 'modules-with-expired-license':
                echo $this->show_expired_licenses_notice( $modules );
                break;
        }
    }

    private function show_modules_no_registered_notice( $modules ) {
        $message = _n( 'Yikes, there has been a mistake. It looks like you have an outdated version of AWPCP <module-name>, or you need a newer version of AWPCP to use that module. Please contact customer support and ask for an update. Please also include a reference to this error in your message.', 'Yikes, there has been a mistake. It looks like you have an outdated version of AWPCP <modules-names>, or you need a newer version of AWPCP to use those modules. Please contact customer support and ask for an update. Please also include a reference to this error in your message.', count( $modules ), 'another-wordpress-classifieds-plugin' );
        $message = $this->replace_modules_names_in_message( $message, $modules );

        return awpcp_print_error( $message );
    }

    private function replace_modules_names_in_message( $message, $modules ) {
        $modules_names = $this->get_modules_names( $modules );

        if ( count( $modules ) === 1 ) {
            $message = str_replace( '<module-name>', $this->get_string_with_names( $modules_names ), $message );
        } else {
            $message = str_replace( '<modules-names>', $this->get_string_with_names( $modules_names ), $message );
        }

        return $message;
    }

    private function get_modules_names( $modules ) {
        foreach ( $modules as $module ) {
            $modules_names[] = $module->name;
        }

        return $modules_names;
    }

    private function get_string_with_names( $names ) {
        if ( count( $names ) === 1 ) {
            $string = '<strong>' . $names[0] . '</strong>';
        } else {
            $n_first_names = '<strong>' . implode( '</strong>, <strong>', array_slice( $names, 0, -1 ) ) . '</strong>';
            $last_name = '<strong>' . end( $names ) . '</strong>';

            /* translators: example: <Extra Fields, Featured Ads> and <Region Control> */
            $string = __( '<comma-separated-names> and <single-name>', 'another-wordpress-classifieds-plugin' );
            $string = str_replace( '<comma-separated-names>', $n_first_names, $string );
            $string = str_replace( '<single-name>', $last_name, $string );
        }

        return $string;
    }

    private function show_required_awpcp_version_notice( $modules ) {
        foreach ( $modules as $module ) {
            echo $module->required_awpcp_version_notice();
        }
    }

    private function show_modules_not_compatible_notice( $modules ) {
        foreach ( $modules as $module ) {
            echo $this->show_module_not_compatible_notice( $module );
        }
    }

    private function show_inactive_licenses_notice( $modules ) {
        $message = _n( 'The license for AWPCP <module-name> is inactive. All features will remain disabled until you activate the license. Please go to the <licenses-settings-link>License Settings</a> section to activate it.', 'The licenses for AWPCP <modules-names> are inactive. The features for those modules will remain disabled until you activate their licenses. Please go to the <licenses-settings-link>License Settings</a> section to activate them.', count( $modules ), 'another-wordpress-classifieds-plugin' );
        return $this->show_license_notice( $message, $modules );
    }

    private function show_license_notice( $message, $modules ) {
        $link = sprintf( '<a href="%s">', awpcp_get_admin_settings_url( 'licenses-settings' ) );

        $message = $this->replace_modules_names_in_message( $message, $modules );
        $message = str_replace( '<licenses-settings-link>', $link, $message );

        return awpcp_print_error( $message );
    }

    private function show_invalid_licenses_notice( $modules ) {
        $message = _n( 'The AWPCP <module-name> requires a license to be used. All features will remain disabled until a valid license is entered. Please go to the <licenses-settings-link>Licenses Settings</a> section to enter or update your license.', 'The AWPCP <modules-names> require a license to be used. The features on each of those modules will remain disabled until a valid license is entered. Please go to the <licenses-settings-link>Licenses Settings</a> section to enter or update your license.', count( $modules ), 'another-wordpress-classifieds-plugin' );
        return $this->show_license_notice( $message, $modules );
    }

    private function show_expired_licenses_notice( $modules ) {
        $message = _n( 'The license for AWPCP <module-name> expired. The module will continue to work but you will not receive automatic updates when a new version is available.', 'The license for AWPCP <modules-names> expired. Those modules will continue to work but you will not receive automatic updates when a new version is available.', count( $modules ), 'another-wordpress-classifieds-plugin' );
        return $this->show_license_notice( $message, $modules );
    }

    private function show_module_not_compatible_notice( $module ) {
        $modules = $this->plugin->get_premium_modules_information();

        $required_version = $modules[ $module->slug ][ 'required' ];

        $message = __( 'This version of AWPCP %1$s is not compatible with AWPCP version %2$s. Please get AWPCP %1$s %3$s or newer!', 'another-wordpress-classifieds-plugin' );
        $message = sprintf( $message, '<strong>' . $module->name . '</strong>', $this->plugin->version, '<strong>' . $required_version . '</strong>' );
        $message = sprintf( '<strong>%s:</strong> %s', __( 'Error', 'another-wordpress-classifieds-plugin' ), $message );

        return awpcp_print_error( $message );
    }

    public function get_modules() {
        return $this->modules;
    }

    public function get_module( $module_slug ) {
        if ( ! isset( $this->modules[ $module_slug ] ) ) {
            throw new AWPCP_Exception( __( 'The specified module does not exists!.', 'another-wordpress-classifieds-plugin' ) );
        }

        return $this->modules[ $module_slug ];
    }
}
