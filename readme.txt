=== OpenID Connect Single Sign-On (SSO) Plugin By Gluu ===
Contributors:  gluu
Donate: https://www.gluu.org/deploy/
Tags: openid connect, oauth 2.0, google login, single sign-on, sso, social login, social authentication, strong authentication, two-factor authentication, multi-factor authentication, 2fa, mfa, acr, dynamic enrollment
Requires at least: 2.0.2
Tested up to: 4.6
Stable tag: 2.4.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin enables user enrollment and authentication against any standard OpenID Provider (OP).

== Description ==
= OpenID Connect Single Sign-On (SSO) Plugin By Gluu =
This plugin enables user enrollment and authentication against any standard OpenID Provider (OP). In order for this plugin to work, you will need:

1) To run a local instance of the oxd OAuth 2.0 client service (commercial software). There are oxd plugins, modules, and extensions for many popular open source platforms and frameworks, including: Wordpress, Magento, OpenCart, SugarCRM, SuiteCRM, Drupal, Roundcube, Spring Framework, Play Framework, Ruby on Rails and Python Flask. 

[Get a license for oxd](http://oxd.gluu.org). 

2) A standard OP that will handle user authentications. If you need an OP you should consider deploying the free open source Gluu Server. 

= Login =
This plugin enables a WordPress site to send users to an external OpenID Provider for login.

= Enrollment = 
If the user has an existing account in the OP, but not in WordPress, this plugin will enable dynamic registration of the user in WordPress. 

= Single Sign-On (SSO) =
By leveraging a central identity provider (IDP) for authentication you can enable single sign-on (SSO) to other web properties that rely on the same authentication system.

= Features =
*    Easy to use WordPress admin UI
*    Bypass the local WP login page and send users straight to the OP for authentication
*    Choice between automatic and role based new user enrollment
*    Assign a universal role to new users that authenticate against the OP
*    Request any authentication mechanism and user scopes made available by the OP

= Website =
*   **Gluu server site :** https://www.gluu.org
*   **Oxd server site :** https://oxd.gluu.org
*   **Documentation :** https://oxd.gluu.org/docs/plugin/wordpress/
*   **Support :** https://support.gluu.org

== Installation ==

= From your WordPress admin dashboard =
1. Visit `Plugins > Add New`.
2. Search for `OpenID Connect Single Sign-On (SSO) Plugin By Gluu`. 
3. Install `OpenID Connect Single Sign-On (SSO) Plugin By Gluu`.
4. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download OpenID Connect Single Sign-On (SSO) Plugin By Gluu.
2. Unzip and upload the `wp_openid_connect_single_sign_on_plugin_by_gluu` directory to your `/wp-content/plugins/` directory.
3. Activate OpenID Connect Single Sign-On (SSO) Plugin By Gluu from your Plugins page.

= Once Activated =
Register an OP, request user scopes, and configure authentication.  

Read the documentation: https://oxd.gluu.org/docs/plugin/wordpress/

== Screenshots ==

1. General page for OpenID Connect Provider, which supports dynamic registration.
2. General page for OpenID Connect Provider, which doesn't support dynamic registration.
3. Edit page for OpenID Connect Provider, which supports dynamic registration.
4. Edit page for OpenID Connect Provider, which doesn't support dynamic registration.
5. OpenID Connect Configuration
6. Frontend login page

== Frequently Asked Questions ==
See the [oxd website](http://oxd.gluu.org).

== Changelog ==

= 2.4.4 =
* Added gluu server url section (op_host).
* Stable version, supported by Gluu Inc.
* Working with gluu and oxd servers version 2.4.4

== Upgrade Notice ==
= 2.4.4 =
* Added gluu server url section (op_host).
* Stable version, supported by Gluu Inc.
* Working with gluu and oxd servers version 2.4.4






