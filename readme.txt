=== Favorites ===
Contributors: kylephillips
Donate link: http://favoriteposts.com/
Tags: favorites, like, bookmark, favorite, likes, bookmarks, favourite, favourites, multisite, wishlist, wish list
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 1.1.3

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Favorites for any post type. Easily add favoriting/liking, wishlists, or any other similar functionality using the developer-friendly API.

== Description ==

**Why Favorites?**

Favorites is designed for end users and theme developers. It provides an easy-to-use API for adding favorite button functionality to any post type.

The plugin can provide a way to save favorites, likes, bookmarks, or any other similar types of data with its customizable button text.

Visit [favoriteposts.com](http://favoriteposts.com) for a full list of available template functions and shortcodes.

**Features**

**Use with Any Post Type** - Enable or disable favorite functionality per post type while automatically adding a favorite button before and/or after the content. Or, use the included functions to display the button anywhere in your template.

**Available for All Users** – Don't want to hide functionality behind a login? Favorites includes an option to save anonymous users' favorites by either Session or Cookie. Logged-In users' favorites are also saved as user meta

**Designed for Developers** - Favorites works great out-of-the-box for beginners, but a full set of template functions unlocks just about any sort of custom functionality developers may need. Favorites outputs the minimum amount of markup needed, putting the style and control in your hands.

**Multisite Compatible** - As of version 1.1.0, Favorites is multisite compatible. User favorites are saved on a site/blog basis, and may be retrieved and displayed across sites.

For more information visit [favoriteposts.com](http://favoriteposts.com).

**Important: Favorites requires WordPress version 3.8 or higher, and PHP version 5.3.2 or higher.**


== Installation ==

1. Upload the favorites plugin directory to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Visit the plugin settings to configure display options
4. Use the template functions, display settings, or shortcodes to display the favorite button, favorite counts, and/or user favorites.

== Frequently Asked Questions ==

= Does this worked on cached pages? =
Yes, although the buttons may display the incorrect state momentarily. Button states are updated via an AJAX call after page load in order to accommodate cached pages. This may be noticeable on slower servers.

= Is this plugin compatible with Multisite? =
As of version 1.1.0, Favorites is compatible with multisite installations. By default, all shortcodes and template functions will pull data from the site being viewed. Specific site IDs may be passed as parameters for more control. See the documentation for more information.


== Screenshots ==

1. Developer-friendly – easily unenqueue styles and scripts if you are combining and minifying your own. 

2. Enable for anonymous users and save in the session or a browser cookie. Logged-in users' favorites are saved in a custom user meta field.

3. Enable and display per post type, or use the functions/shortcodes to manually add to templates.

4. Add a favorites button to any post type. Customize the button text and/or icon.

5. Use the included functions or shortcodes to display a list of user favorites. A template function is also provided to fetch an array of user favorite post ids.


== Changelog ==

= 1.1.4 =
* Fixed bug that allowed multiple button submissions before the previous was processed

= 1.1.3 =
* Option added to hide loading indication on page load.

= 1.1.2 =
* Bug fix in plugin settings when deselecting all automatic post type insertions.

= 1.1.1 =
* Optional filtering added to favorite list template functions. The functions now accept an array of arguments for fine-tuned favorite lists. Visit the documentation for more information.
* An optional "post_type" parameter has been added to the user_favorites shortcode. The parameter allows for filtering of the generated list by post types, and will accept a comma separated list of post types
* Option added to include post favorite count in the button (view settings > favorites > display to enable)
* Button loading state option added (view settings > favorites > display to enable)
* Added template function and shortcode for displaying total number of favorites by user (see documentation for more options)

= 1.1.0 =
* Favorites is now multisite compatible. See documentation for added template function and shortcode parameters.

= 1.0.5 =
* Autoloader bug fix (Thanks to Stefan Oderbolz)
* User List page cache fix

= 1.0.4 =
* User favorites list bug fix

= 1.0.3 =
* Additional bug fixes for logged in users

= 1.0.2 =
* Fixed array error bug for logged in users

= 1.0.1 =
* Fixed bug where logged in user's favorites were pulling from session/cookie rather than saved user meta
* Tested for 4.2 compatibility

= 1.0 =
* Initial release 


== Upgrade Notice ==

= 1.1.3 =
* Option added to hide loading indication on page load. By default, loading indication is turned off. To turn loading indication on, visit settings > favorites > display, and select the checkbox labeld "include loading indicator image on page load"

= 1.1.1 =
* Optional filters added to favorite list functionality. Visit the documentation page for more information.

= 1.1.0 =
* Favorites is now multisite compatible. See documentation for added template function and shortcode parameters.

= 1.0 =
Initial release

== Usage ==

**Favorite Button**

The favorite button can be added automatically to the content by enabling specific post types in the plugin settings. It may also be added to template files or through the content editor using the included functions or shortcodes. The post id may be left blank in all cases if inside the loop. The site id parameter is optional, for use in multisite installations (defaults to current site).

* **Get function:** `get_favorites_button($post_id, $site_id)`
* **Print function:** `the_favorites_button($post_id, $site_id)`
* **Shortcode:** `[favorite_button post_id="" site_id=""]`

**Favorite Count (by Post)**

Total favorites for each post are saved as a simple integer. If a user unfavorites a post, this count is updated. Anonymous users' favorites count towards the total by default, but may be disabled via the plugin settings. The post id may be left blank in all cases if inside the loop.

* **Get function:** `get_favorites_count($post_id)`
* **Print function:** `the_favorites_count($post_id)`
* **Shortcode:** `[favorite_count post_id=""]`

**Favorite Count (by User)**
Displays the total number of favorites a user has favorited. Template functions accept the same filters parameter as the user favorites functions.

* **Get function:** `get_user_favorites_count($user_id, $site_id, $filters)`
* **Print function:** `the_user_favorites_count($user_id, $site_id, $filters)`
* **Shortcode:** `[user_favorites user_id="" site_id="" post_types=""]`

**User Favorites**

User favorites are stored as an array of post ids. Logged-in users' favorites are stored as a custom user meta field, while anonymous users' favorites are stored in either the session or browser cookie (configurable in the plugin settings). If the user id parameter is omitted, the favorites default to the current user. The site id parameter is optional, for use in multisite installations (defaults to current site).

* **Get function (returns array of IDs):** `get_user_favorites($user_id, $site_id)`
* **Get function (returns html list):** `get_user_favorites_list($user_id, $site_id)`
* **Print function (prints an html list):** `the_user_favorites_list($user_id, $site_id)`
* **Shortcode (prints an html list, with the option of omitting links):** `[user_favorites user_id="" include_links="true" site_id=""]
