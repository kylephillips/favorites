# Favorites for Wordpress

## Overview

Favorites is a WordPress plugin designed for end users and theme developers. It provides an easy-to-use API for adding favorite button functionality to any post type.

The plugin name is “Favorites,” but the button text is customizable. It can provide a way to save favorites, likes, bookmarks, or any other similar types of data.

### Demo 
[View the Demo](http://favoriteposts.com)


### Installation - WP Directory Download
1. Upload the favorites plugin directory to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Visit the plugin settings to configure display options
4. Use the template functions, display settings, or shortcodes to display the favorite button, favorite counts, and/or user favorites.

### Installation - Git Clone
1. Clone the repository to your site plugins directory
2. Run `composer install`
3. Activate the plugin through the Plugins menu in WordPress
4. Visit the plugin settings to configure display options
5. Use the template functions, display settings, or shortcodes to display the favorite button, favorite counts, and/or user favorites.


### Usage

#### Favorite Button
The favorite button can be added automatically to the content by enabling specific post types in the plugin settings. It may also be added to template files or through the content editor using the included functions or shortcodes. The post id may be left blank in all cases if inside the loop. The site id parameter is optional, for use in multisite installations.

- **Get function:** `get_favorites_button($post_id, $site_id)`
- **Print function:** `the_favorites_button($post_id, $site_id)`
- **Shortcode:** `[favorite_button post_id="" site_id=""]`

#### Favorite Count (by Post)
Total favorites for each post are saved as a simple integer. If a user unfavorites a post, this count is updated. Anonymous users' favorites count towards the total by default, but may be disabled via the plugin settings. The post id may be left blank in all cases if inside the loop.

- **Get function:** `get_favorites_count($post_id)`
- **Print function:** `the_favorites_count($post_id)`
- **Shortcode:** `[favorite_count post_id=""]`

#### Favorite Count (by User)
Displays the total number of favorites a user has favorited. Template functions accept the same filters parameter as the user favorites functions.

- **Get function:** `get_user_favorites_count($user_id, $site_id, $filters)`
- **Print function:** `the_user_favorites_count($user_id, $site_id, $filters)`
- **Shortcode:** `[user_favorites user_id="" site_id="" post_types=""]`

#### Favorite Count (All Users, All Posts)
Displays the total number of favorites across an entire site.

- **Get function:** `get_total_favorites_count($site_id)`
- **Print function:** `the_total_favorites_count($site_id)`

#### User Favorites
User favorites are stored as an array of post ids. Logged-in users' favorites are stored as a custom user meta field, while anonymous users' favorites are stored in either the session or browser cookie (configurable in the plugin settings). If the user id parameter is omitted, the favorites default to the current user. The site id parameter is optional, for use in multisite installations.

- **Get function (returns array of IDs):** `get_user_favorites($user_id, $site_id, $filters)`
- **Get function (returns html list):** `get_user_favorites_list($user_id, $site_id, $include_links, $filters,  include_thumbnails="false" thumbnail_size="thumbnail" include_excerpt="false")`
- **Print function (prints an html list):** `the_user_favorites_list($user_id, $site_id, $include_links, $filters,  include_thumbnails="false" thumbnail_size="thumbnail" include_excerpt="false")`
- **Shortcode (prints an html list, with the option of omitting links):** `[user_favorites user_id="" include_links="true" site_id="" post_types="",  include_thumbnails="false" thumbnail_size="thumbnail" include_excerpt="false"]`

#### Clear Favorites Button
The clear favorites button clears out all of the current user's favorites.

- **Get function:** `get_clear_favorites_button($site_id, $button_text)`
- **Print function:** `the_clear_favorites_button($site_id, $button_text)`
- **Shortcode:** `[clear_favorites_button siteid="" text=""]`
