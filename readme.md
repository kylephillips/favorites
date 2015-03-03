# Favorites for Wordpress

## Overview

Favorites is a WordPress plugin designed for end users and theme developers. It provides an easy-to-use API for adding favorite button functionality to any post type.

The plugin name is “Favorites,” but the button text is customizable. It can provide a way to save favorites, likes, bookmarks, or any other similar types of data.

### Demo 
[View the Demo](http://favoriteposts.com)


### Installation 
1. Upload the favorites plugin directory to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Visit the plugin settings to configure display options
4. Use the template functions, display settings, or shortcodes to display the favorite button, favorite counts, and/or user favorites.


### Usage

#### Favorite Button
The favorite button can be added automatically to the content by enabling specific post types in the plugin settings. It may also be added to template files or through the content editor using the included functions or shortcodes. The post id may be left blank in all cases if inside the loop.

**Get function:** `get_simple_favorites_button($post_id)`
**Print function:** `simple_favorites_button($post_id)`
**Shortcode:** `[favorite_button post_id=""]`

#### Favorite Count
Total favorites for each post are saved as a simple integer. If a user unfavorites a post, this count is updated. Anonymous users' favorites count towards the total by default, but may be disabled via the plugin settings. The post id may be left blank in all cases if inside the loop.

**Get function:** `get_simple_favorites_count($post_id)`
**Print function:** `simple_favorites_count($post_id)`
**Shortcode:** `[favorite_count post_id=""]`

#### User Favorites
User favorites are stored as an array of post ids. Logged-in users' favorites are stored as a custom user meta field, while anonymous users' favorites are stored in either the session or browser cookie (configurable in the plugin settings). If the user id parameter is omitted, the favorites default to the current user.

**Get function (returns array of IDs):** `get_simple_favorites_user_favorites($user_id)`
**Get function (returns html list):** `get_simple_favorites_user_list($user_id)`
**Print function (prints an html list):** `simple_favorites_user_list($user_id)`
**Shortcode (prints an html list, with the option of omitting links):** `[user_favorites user_id="" include_links="true"]
