/**
 * Native Events
 */
var Favorites = Favorites || {};

Favorites.NativeEvents = function()
{
  var plugin = this;
  var $ = jQuery;

  plugin.bindEvents = function()
  {
    $(document).on('favorites-updated-single', function (event, favorites, post_id, site_id, status) {
      const singleUpdateEvent = plugin.createEvent('favorites-updated-single-native', {
        status,
        site_id,
        post_id,
        favorites,
      })

      document.dispatchEvent(singleUpdateEvent)
    });

  }

  /**
   * Create a custom Javascript event
   * @param name    {string}
   * @param details {object}
   * @return        {CustomEvent}
  */
  plugin.createEvent = function (name, details = null) {
    return new CustomEvent(name, {
      bubbles: true,
      detail: details,
    })
  }

  return plugin.bindEvents();
}