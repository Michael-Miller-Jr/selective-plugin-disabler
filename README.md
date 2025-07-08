# Selective Plugin Disabler

Disables Plugins Instead of Deactivating Them - Retain Those License Keys!

Selective Plugin Disabler is a WordPress Must-Use (MU) plugin designed to simplify the process of troubleshooting and resolving plugin-related issues on your WordPress site. This tool allows administrators to "soft-disable" specific plugins without fully deactivating them, preserving settings and avoiding potential license reactivation issues during debugging.

Additionally, it provides a custom login URL that allows access to the plugin management interface even when the WordPress admin area is inaccessible due to a critical error. This ensures site administrators have a reliable way to regain control of their WordPress site under any circumstances.

The plugin also includes a built-in reminder to remove it once troubleshooting is complete, ensuring there’s no unnecessary functionality left active on your site for security purposes.

---

## Features

### Selective Disabling of Plugins
- Temporarily disable plugins without fully deactivating them.
- Avoid losing plugin settings or requiring license reactivation.

### Custom Login URL
- Access the plugin management interface through a custom URL (`?sp_custom_login`).
- Ensures troubleshooting access even when the admin area is down.

### Secure Admin-Only Access
- Only users with administrator login credentials can access the plugin settings.
- Built with nonce verification to prevent unauthorized changes.

### Built-In Security Reminder
- Includes a notice within the plugin management interface reminding administrators to remove the plugin after troubleshooting is complete.
- This ensures no unnecessary code or functionality is left active, reducing the risk of potential security vulnerabilities.

### Efficient Troubleshooting
- Quickly disable problematic plugins and restore site functionality without navigating the full admin dashboard.

### Responsive Interface
- Clean, modern, and intuitive interface, accessible on all devices.

---

## Use Cases

### Troubleshooting Plugin Conflicts
- Identify and isolate conflicts by disabling problematic plugins temporarily.

### Recovering from Critical Errors
- Use the custom login URL to disable problematic plugins when the admin dashboard is inaccessible.

### Preserving Plugin Licenses and Settings
- Avoid the hassle of re-entering license keys or losing settings for premium plugins during testing or debugging.

### Advanced Testing and Debugging
- Safely test site functionality with specific plugins disabled.

---

## Security Note

To maintain a secure and streamlined site, the plugin management interface includes a prominent notice reminding administrators to remove the plugin after completing their troubleshooting or debugging tasks. Leaving unnecessary functionality active on your site can introduce potential vulnerabilities, which this plugin aims to avoid.

---

## Installation Instructions

### Upload the Plugin
1. Place the `Selective Plugin Disabler` file into the `/wp-content/mu-plugins/` directory.
2. If the `mu-plugins` directory doesn’t exist, create it inside `/wp-content/`.

### Access the Plugin Management Interface
- **Admin Dashboard**: Navigate to "Selective Plugin Disabler" from the WordPress settings menu.
- **Custom URL**: Visit `https://yoursite.com/?sp_custom_login` to access the plugin management page directly, even outside the admin dashboard.

---

## Usage

### Login
- Use the WordPress admin dashboard or the custom login page (`?sp_custom_login`) to authenticate.

### Disable Plugins
- Select plugins to disable from the list provided in the interface. These plugins will stop functioning without being deactivated.

### Restore Plugins
- Deselect the plugins you want to re-enable, and save your changes. The plugins will resume functioning normally.

### Remove the Plugin
- Once you’ve completed troubleshooting, delete the `Selective Plugin Disabler` from the `/mu-plugins/` directory. This is a critical security step to ensure no unnecessary functionality remains active.

---

## Why This Plugin?

Selective Plugin Disabler offers administrators peace of mind by providing a quick and reliable way to handle plugin conflicts, test changes, or recover from errors. It’s an indispensable tool for maintaining site stability without compromising plugin configurations or security.

---

## How Selective Plugin Disabler Works

This plugin operates by intercepting WordPress’s active plugins list and selectively removing certain plugins from it. Here’s a step-by-step breakdown of the process:

### Mechanism

#### Intercepting the Active Plugins List
- WordPress maintains a list of "active plugins" stored in the `active_plugins` option in the database.
- The plugin uses the `option_active_plugins` filter to modify this list dynamically at runtime.

#### Excluding Plugins Without Deactivating Them
- The plugin compares the current list of active plugins against the ones marked for "disabling" in the `sp_disabled_plugins` option.
- Any plugin marked as "disabled" is removed from the active plugins list before WordPress initializes those plugins.

#### Result
- The excluded plugins are not loaded by WordPress during the request lifecycle, effectively "disabling" their functionality for that specific session.

---

## How Is "Disabled" Different from "Deactivated"?

### Disabled
- **How It Works**: The plugin stops the selected plugins from being loaded by WordPress. Their code and functionalities are effectively ignored during that session.
- **Preserves Settings**: Since the plugin isn't officially deactivated, its settings and configurations remain untouched.
- **No License Reactivation Needed**: Some premium plugins require license reactivation when deactivated. By "disabling" them instead, the license remains intact.
- **Temporary**: Disabling plugins this way is reversible by unchecking them in the plugin's interface.

### Deactivated
- **How It Works**: When a plugin is deactivated, WordPress updates the `active_plugins` option in the database to remove it permanently (until reactivated).
- **Plugins Are Fully Unloaded**: WordPress treats deactivated plugins as if they don’t exist during runtime.
- **Can Trigger License Reactivation**: Premium plugins often consider deactivation as an event that invalidates their license.

---

## Edge Cases: Could Plugins Still Run?

In rare scenarios, certain plugins may still partially run due to how their code is structured:

### Plugins Loaded Outside of `option_active_plugins`
- If a plugin loads itself through a custom mechanism (e.g., `mu-plugins`, autoloaders, or directly included in the theme), it could bypass the disabling mechanism.
- Such plugins would not respect the `option_active_plugins` filter.

### Dependencies or Shared Code
- If a disabled plugin shares code or hooks with an active plugin, some of its functionalities might still run indirectly.
- Example: A shared library used by both the disabled plugin and another active plugin.

### Direct Inclusion in Themes
- If a theme directly includes plugin files (e.g., via `require` or `include`), the plugin's functionality could still execute.

---

## Key Advantages of Disabling via This Plugin
- **Safe Troubleshooting**: Temporarily disable plugins without disrupting site settings or configurations.
- **No Permanent Changes**: Unlike deactivation, "disabling" is session-based and can be reverted quickly.
- **Avoid Re-Entering Licenses**: Preserves licenses and activation states, making the process faster and less intrusive.

---

## When Might This Not Fully Work?

### Plugins in `mu-plugins`
- Plugins in the `mu-plugins` directory (like this plugin itself) are loaded automatically by WordPress and cannot be disabled using this method.

### Hard-Coded Plugin Calls
- Plugins explicitly included in the `functions.php` file or elsewhere in the theme cannot be managed by this plugin.

---

## Conclusion

This plugin effectively "disables" plugins by dynamically modifying WordPress’s active plugins list, but it relies on WordPress’s default mechanisms. In almost all scenarios, this method is sufficient for troubleshooting or temporarily disabling plugins without the side effects of full deactivation. However, for plugins with unconventional loading methods, some functionality might persist.

## License

Proprietary / All Rights Reserved (No license declared)
- If a project has no license, you cannot legally reuse, copy, modify, or distribute the code. All rights are reserved by default under copyright law.
