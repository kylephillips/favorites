=== Favorites ===
Contributors: kylephillips
Donate link: http://favoriteposts.com/
Tags: favorites, like, bookmark, favorite, likes, bookmarks, favourite, favourites
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 1.0.1

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple and flexible favorite buttons for any post type.

== Description ==

**Why Favorites?**

Favorites is designed for end users and theme developers. It provides an easy-to-use API for adding favorite button functionality to any post type.

The plugin can provide a way to save favorites, likes, bookmarks, or any other similar types of data with its customizable button text.

Visit [favoriteposts.com](http://favoriteposts.com) for a full list of available template functions and shortcodes.

**Features**

**Use with Any Post Type** - Enable or disable favorite functionality per post type while automatically adding a favorite button before and/or after the content. Or, use the included functions to display the button anywhere in your template.

**Available for All Users** – Don't want to hide functionality behind a login? Favorites includes an option to save anonymous users' favorites by either Session or Cookie. Logged-In users' favorites are also saved as user meta

**Designed for Developers** - Favorites works great out-of-the-box for beginners, but a full set of template functions unlocks just about any sort of custom functionality developers may need. Favorites outputs the minimum amount of markup needed, putting the style and control in your hands.

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


== Screenshots ==

1. Developer-friendly – easily unenqueue styles and scripts if you are combining and minifying your own. 

2. Enable for anonymous users and save in the session or a browser cookie. Logged-in users' favorites are saved in a custom user meta field.

3. Enable and display per post type, or use the functions/shortcodes to manually add to templates.


== Changelog ==

= 1.0.2 =
* Fixed array error bug for logged in users

= 1.0.1 =
* Fixed bug where logged in user's favorites were pulling from session/cookie rather than saved user meta
* Tested for 4.2 compatibility

= 1.0 =
* Initial release 


== Upgrade Notice ==

= 1.0 =
Initial release

== Usage ==

**Favorite Button**

The favorite button can be added automatically to the content by enabling specific post types in the plugin settings. It may also be added to template files or through the content editor using the included functions or shortcodes. The post id may be left blank in all cases if inside the loop.

* **Get function:** `get_favorites_button($post_id)`
* **Print function:** `the_favorites_button($post_id)`
* **Shortcode:** `[favorite_button post_id=""]`

**Favorite Count**

Total favorites for each post are saved as a simple integer. If a user unfavorites a post, this count is updated. Anonymous users' favorites count towards the total by default, but may be disabled via the plugin settings. The post id may be left blank in all cases if inside the loop.

* **Get function:** `get_favorites_count($post_id)`
* **Print function:** `the_favorites_count($post_id)`
* **Shortcode:** `[favorite_count post_id=""]`

**User Favorites**

User favorites are stored as an array of post ids. Logged-in users' favorites are stored as a custom user meta field, while anonymous users' favorites are stored in either the session or browser cookie (configurable in the plugin settings). If the user id parameter is omitted, the favorites default to the current user.

* **Get function (returns array of IDs):** `get_user_favorites($user_id)`
* **Get function (returns html list):** `get_user_favorites_list($user_id)`
* **Print function (prints an html list):** `the_user_favorites_list($user_id)`
* **Shortcode (prints an html list, with the option of omitting links):** `[user_favorites user_id="" include_links="true"]
