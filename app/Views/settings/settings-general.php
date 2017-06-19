<?php settings_fields( 'simple-favorites-general' ); ?>

<h3><?php _e('Page Cache', 'favorites'); ?></h3>
<div class="simple-favorites-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplefavorites_cache_enabled" value="true" <?php if ( $this->settings_repo->cacheEnabled() ) echo 'checked'; ?> />
		</div>
		<div class="post-type-name">
			<?php _e('Cache Enabled on Site (Favorites content is injected on page load with AJAX request)', 'favorites'); ?>
		</div>
	</div><!-- .post-type-row -->
</div><!-- .simple-favorites-post-types -->

<h3><?php _e('Development Mode', 'favorites'); ?></h3>
<div class="simple-favorites-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplefavorites_dev_mode" value="true" <?php if ( $this->settings_repo->devMode() ) echo 'checked'; ?> />
		</div>
		<div class="post-type-name">
			<?php _e('Enable Development Mode (logs JS responses in the console for debugging)'); ?>
		</div>
	</div><!-- .post-type-row -->
</div><!-- .simple-favorites-post-types -->

<h3><?php _e('Dependencies', 'favorites'); ?></h3>
<div class="simple-favorites-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Enqueue Plugin CSS', 'favorites'); ?></h5>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplefavorites_dependencies[css]" value="true" data-favorites-dependency-checkbox <?php if ( $this->settings_repo->outputDependency('css') ) echo 'checked'; ?> /><?php _e('Output Plugin CSS', 'favorites'); ?>
			</label>
			<div class="simplefavorites-dependency-content" data-favorites-dependency-content>
				<p><em><?php _e('If you are compiling your own minified CSS, include the CSS below:', 'favorites'); ?></em></p>
				<textarea><?php echo Favorites\Helpers::getFileContents('assets/css/styles-uncompressed.css'); ?></textarea>
			</div>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Enqueue Plugin Javascript', 'favorites'); ?></h5>
			<p><?php _e('Important: The plugin JavaScript is required for core functions. If this is disabled, the plugin JS <strong>must</strong> be included with the theme along with the global JS variables.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block">
				<input type="checkbox" name="simplefavorites_dependencies[js]" value="true" data-favorites-dependency-checkbox <?php if ( $this->settings_repo->outputDependency('js') ) echo 'checked'; ?> /><?php _e('Output Plugin JavaScript', 'favorites'); ?>
			</label>
			<div class="simplefavorites-dependency-content" data-favorites-dependency-content>
				<p><em><?php _e('If you are compiling your own minified Javascript, include the below (required for plugin functionality):', 'favorites'); ?></em></p>
				<textarea><?php echo Favorites\Helpers::getFileContents('assets/js/favorites.js'); ?></textarea>
			</div>
		</div>
	</div><!-- .row -->
</div><!-- .favorites-display-settings -->

<div class="favorites-alert">
	<p><strong><?php _e('Favorites Version', 'favorites'); ?>:</strong> <?php echo Favorites\Helpers::version(); ?></p>
</div>