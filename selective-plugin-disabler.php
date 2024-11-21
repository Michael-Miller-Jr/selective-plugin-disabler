<?php
/**
 * Plugin Name: Selective Plugin Disabler MU
 * Description: Allows selective disabling of plugin functionalities without deactivating them. Includes a custom login page for managing plugin status even during site errors.
 * Version: 1.2
 * Author: Michael Miller Jr
 * Author URI: https://www.linkedin.com/in/michael-r-miller-jr
 * Plugin URI: https://github.com/Michael-Miller-Jr
 */

// Standalone login page with settings accessible via a unique URL https://yoursite.com/?sp_custom_login

// Initialize options on plugin activation
function sp_disable_plugin_setup_mu() {
    if (get_option('sp_disabled_plugins') === false) {
        add_option('sp_disabled_plugins', []);
    }
}
sp_disable_plugin_setup_mu();

// Modify the active plugins list to exclude selected plugins
add_filter('option_active_plugins', 'sp_filter_active_plugins');
function sp_filter_active_plugins($active_plugins) {
    $disabled_plugins = get_option('sp_disabled_plugins', []);

    if (!empty($disabled_plugins)) {
        // Exclude disabled plugins from the active plugins list
        $active_plugins = array_diff($active_plugins, $disabled_plugins);
    }

    return $active_plugins;
}

// Admin page for managing disabled plugins
add_action('admin_menu', 'sp_plugin_management_menu');
function sp_plugin_management_menu() {
    add_menu_page(
        'Selective Plugin Disabler',
        'Plugin Disabler',
        'manage_options',
        'sp_plugin_disabler',
        'sp_plugin_management_page'
    );
}

// Function for displaying the plugin management page
function sp_plugin_management_page() {
    sp_display_plugin_management_interface();
}

// Custom login page with embedded plugin management interface
add_action('template_redirect', 'sp_custom_login_page');
function sp_custom_login_page() {
    if (isset($_GET['sp_custom_login'])) {
        if (is_user_logged_in()) {
            // Show plugin management interface directly on the custom page
            sp_display_plugin_management_interface();
        } else {
            // Show login form if the user is not logged in
            sp_load_custom_login();
        }
        exit;
    }
}

// Display login form for custom login page, submitting to wp-login.php
function sp_load_custom_login() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login - Selective Plugin Disabler</title>
        <style>
            .login-form {
                max-width: 400px;
                margin: 100px auto;
                padding: 20px;
                border: 1px solid #ccc;
                background: #f9f9f9;
                text-align: center;
            }
            .error-message {
                color: red;
                font-size: 14px;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo wp_login_url(site_url('?sp_custom_login')); ?>" method="POST">
            <label>Username or Email: <input type="text" name="log" required></label><br><br>
            <label>Password: <input type="password" name="pwd" required></label><br><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($_GET['login_error'])): ?>
            <p class="error-message">Invalid credentials. Please try again.</p>
        <?php endif; ?>
    </div>
    </body>
    </html>
    <?php
}

// Function to display the plugin management interface, reusable for admin and custom page
function sp_display_plugin_management_interface() {
    // Ensure user has permissions to manage options
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this page.');
    }

    $all_plugins = get_plugins();
    $disabled_plugins = get_option('sp_disabled_plugins', []);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sp_disabled_plugins_nonce']) && wp_verify_nonce($_POST['sp_disabled_plugins_nonce'], 'sp_disable_plugins_action')) {
        $new_disabled_plugins = isset($_POST['sp_disabled_plugins']) ? array_map('sanitize_text_field', $_POST['sp_disabled_plugins']) : [];
        update_option('sp_disabled_plugins', $new_disabled_plugins);

        // Reload page to reflect saved settings
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

    ?>
    <div class="wrap sp-plugin-disabler" style="max-width: 700px; margin: auto; padding-top: 20px;">
        <h1>Selective Plugin Disabler Settings</h1>

        <div class="notice notice-warning" style="margin-bottom: 20px;">
            <p><strong>Reminder:</strong> Once you're done troubleshooting or fixing the plugin issue on your site, please uninstall this plugin to avoid leaving unnecessary functionality active.</p>
        </div>

        <div class="sp-settings-panel">
            <form method="POST">
                <?php wp_nonce_field('sp_disable_plugins_action', 'sp_disabled_plugins_nonce'); ?>
                <h2>Select Plugins to Disable</h2>
                <div class="sp-plugin-list" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php foreach ($all_plugins as $path => $details): ?>
                        <label class="sp-plugin-item" style="flex: 1 1 200px; background: #fff; border: 1px solid #ddd; padding: 8px 12px; border-radius: 4px;">
                            <input type="checkbox" name="sp_disabled_plugins[]" value="<?php echo esc_attr($path); ?>" <?php checked(in_array($path, $disabled_plugins)); ?>>
                            <?php echo esc_html($details['Name']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" style="margin-top: 20px; padding: 10px 20px; background-color: #0073aa; color: #fff; border: none; border-radius: 4px;">Save Changes</button>
            </form>
        </div>
    </div>
    <?php
}
