=== Simple Password Protection ===
Contributors: dballari
Donate link: https://pay.sumup.com/b2c/QFMKLYCT
Tags: password, protection
Tested up to: 6.6.2
Requires at least: 6.5
Stable tag: 0.0.3
Requires PHP: 7.0
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds password protection to your WordPress site, blocking access until the correct password is entered, ideal for preventing search engine indexing.

== Description ==

This WordPress plugin provides a simple and effective way to restrict access to your website content. When activated, it displays a password-protected landing page that blocks all visitors until the correct password is entered. Perfect for scenarios where you need a basic barrier to prevent indexing by search engines while allowing access to selected individuals. Designed to be lightweight and easy to use, it offers a straightforward solution for website owners who only need basic access control without complex security measures.

The plugin offers flexible password configuration through the wp-config.php file:

Single Site: For single-site setups, you can define the MY_PASSWORDS constant as a string in wp-config.php. This will serve as the password for accessing the site. For example:

´´´
define('MY_PASSWORDS', 'your_password_here');
´´´

Multisite: In a multisite environment, MY_PASSWORDS can be defined as an associative array, where each key is the site ID, and each value is the respective password for that site. For example:

´´´
define('MY_PASSWORDS', [
    1 => 'password_for_site_1',
    2 => 'password_for_site_2',
    // Add more sites as needed
]);
´´´

If MY_PASSWORDS is not set, the plugin defaults to a basic password defined within the code itself, which can be modified directly in the plugin file. This flexible approach allows for easy, code-level customization and selective access across different site environments.

Simply set a password, share it with those who need access, and enjoy quick, no-fuss protection.

== Changelog ==

= 0.0.3 =
* Added nonces

= 0.0.2 =
* Determine password based on MY_PASSWORDS constant
* Add translatable strings in english

= 0.0.1 =
* Initial version in spanish
