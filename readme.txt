=== OpenID Connect Single Sign-On (SSO) Plugin By Gluu ===
Contributors:  gluu
Donate link: https://www.gluu.org/deploy/
Tags: google plus login, u2f token, fido login, gluu basic login, gluu, duo, oauth, oxpush, auto user registration, auto-login, autologin, openid connect, single sign-on, social authentication,social sign-in, SSO technology
Requires at least: 2.0.2
Tested up to: 4.6
Stable tag: 2.4.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin will enable you to authenticate users against any standard OpenID Connect Provider.

== Description ==
= OpenID Connect Single Sign-On (SSO) Plugin By Gluu =
This plugin will enable you to authenticate users against any standard OpenID Connect Provider. In order for this plugin to work you'll need two things in addition to the plugin:

1) You’ll need to run a local oxd OpenID Connect client service. The oxd middleware service is easy to install, and makes it easier to keep up-to-date with the latest security fixes for OAuth2. There are oxd plugins, modules and extensions for many popular platforms and frameworks like: Wordpress, Magento, OpenCart, SugarCRM, SuiteCRM, Drupal, Roundcube, Spring Framework, Play Framework, Ruby on Rails and Python Flask.

2) You'll also need to a standard OpenID Connect Provider (OP), like Google or the Gluu Server, that will handle the user authentications. If you are also looking for a modern access management platform, you should consider the Gluu Server Community Edition. The Gluu Server includes an OpenID Connect Provider that will enable you to create local accounts for people in your domain, and to manage single sign-on (SSO) across your websites.

Using this plugin, you’ll be able to request authentication mechanisms supported by the OP using the OpenID Connect “acr” parameter. You may want to use strong two-factor authentication (2FA), or social login to Google, Facebook or other popular sites. If you're using the Gluu Server as your OP, there are a few out-of-the-box options for authentication, including basic username/password, FIDO U2F tokens, Duo, and Super Gluu--Gluu's free mobile two factor authentication app.

= Login =
This plugin enables a WordPress site to send users to an external OpenID Connect provider for login.

= Easy Integration =
Simply add the `OpenID Connect By Gluu - OpenID Connect Single Sign-On` widget to display a login button in your sites widget area. Add the shortcode [gluu_login shape="oval" theme="default" space="5" size="40"] to add a login button in other places.

= Single Sign-On (SSO) =
By leveraging a central identity provider for authentication you can enable single sign-on (SSO) for your users to other web properties that rely on the same authentication system.

= Features - =
*    Easy to use WordPress admin UI
*    Leverage stronger authentication mechanisms, like Fido U2F tokens, Google+, Duo, Super Gluu.
*    Optional automatic user registration after login if the user is not already registered with your site.
*    Assign a universal role to users registering during login.
*    Get **support** by opening an issue on https://support.gluu.org.

= Website =
*   **Gluu server site :** https://www.gluu.org
*   **Oxd server site :** https://oxd.gluu.org
*   **Documentation :** https://oxd.gluu.org/docs/plugin/wordpress/
*   **Support :** https://support.gluu.org

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `OpenID Connect Single Sign-On (SSO) Plugin By Gluu`. Find and Install `OpenID Connect Single Sign-On (SSO) Plugin By Gluu`.
3. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download OpenID Connect Single Sign-On (SSO) Plugin By Gluu.
2. Unzip and upload the `wp_openid_connect_single_sign_on_plugin_by_gluu` directory to your `/wp-content/plugins/` directory.
3. Activate OpenID Connect Single Sign-On (SSO) Plugin By Gluu from your Plugins page.

= Once Activated =
Read documentation step by step.
Documentation : https://oxd.gluu.org/docs/plugin/wordpress/

== Frequently Asked Questions ==

= I need login with other SSO apps like U2F Fido token, Google+, Duo, OxPush, Gluu Basic etc. ? =
Please visit to support website https://support.gluu.org.

== Screenshots ==

1. General page for OpenID Connect Provider, which supports dynamic registration.
2. General page for OpenID Connect Provider, which doesn't support dynamic registration.
3. Edit page for OpenID Connect Provider, which supports dynamic registration.
4. Edit page for OpenID Connect Provider, which doesn't support dynamic registration.
5. OpenID Connect Configuration
6. Frontend login page

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

