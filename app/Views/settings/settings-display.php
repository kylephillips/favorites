<?php settings_fields( 'simple-favorites-display' ); ?>

<h3><?php _e('Enabled Favorites for:', 'favorites'); ?></h3>
<div class="simple-favorites-post-types">
	<?php 
		foreach ( $this->post_type_repo->getAllPostTypes() as $posttype ) : 
		$post_type_object = get_post_type_object($posttype);
		$display = $this->settings_repo->displayInPostType($posttype);
	?>
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][display]" value="true" <?php if ( $display ) echo ' checked'; ?> data-favorites-posttype-checkbox />
		</div>
		<div class="post-type-name">
			<?php echo $post_type_object->labels->name; ?>
			<button class="button" data-favorites-toggle-post-type-settings <?php if ( !$display ) echo 'style="display:none;"';?>><?php _e('Settings', 'favorites'); ?></button>
		</div>
		<div class="post-type-settings">
			<div class="row">
				<div class="description">
					<h5><?php _e('Insert Favorite button before content', 'favorites') ?></h5>
					<p><?php _e('Favorite buttons are automatically inserted before the content using the_content filter.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][before_content]" value="true" <?php if ( isset($display['before_content']) ) echo ' checked'; ?>/> <?php _e('Include before content', 'favorites'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Insert Favorite button after content', 'favorites') ?></h5>
					<p><?php _e('Favorite buttons are automatically inserted after the content using the_content filter.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][after_content]" value="true" <?php if ( isset($display['after_content']) ) echo ' checked'; ?>/> <?php _e('Include after content', 'favorites'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Show favorite count on post edit screen', 'favorites') ?></h5>
					<p><?php _e('Adds a meta box with the total number of favorites the post has received.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][postmeta]" value="true" <?php if ( isset($display['postmeta']) ) echo ' checked'; ?>/> <?php _e('Add meta box', 'favorites'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Show favorite count in admin columns', 'favorites') ?></h5>
					<p><?php _e('Adds a column to the admin listing with the total favorite count.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][admincolumns]" value="true" <?php if ( isset($display['admincolumns']) ) echo ' checked'; ?>/> <?php _e('Add admin column', 'favorites'); ?>
				</div>
			</div><!-- .row -->
		</div><!-- .post-type-settings -->
	</div><!-- .post-type-row -->
	<?php endforeach; ?>
</div><!-- .simple-favorites-post-types -->

<h3><?php _e('Favorite Button Content', 'favorites'); ?></h3>
<div class="simple-favorites-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Button Text: Unfavorited', 'favorites'); ?></h5>
			<p><?php _e('The button text, in an unfavorited state.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Text/HTML', 'favorites'); ?></label>
			<input type="text" name="simplefavorites_display[buttontext]" value="<?php echo $this->settings_repo->buttonText(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Button Text: Favorited', 'favorites'); ?></h5>
			<p><?php _e('The button text, in a favorited state.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Text/HTML', 'favorites'); ?></label>
			<input type="text" name="simplefavorites_display[buttontextfavorited]" value="<?php echo $this->settings_repo->buttonTextFavorited(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Include Total Favorite Count', 'favorites'); ?></h5>
			<p><?php _e('Adds the total number of times the post has been favorited to the button.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label><input type="checkbox" name="simplefavorites_display[buttoncount]" value="true" <?php if ( $this->settings_repo->includeCountInButton() ) echo 'checked'; ?> /> <?php _e('Include count in button', 'favorites'); ?></label>
		</div>
	</div><!-- .row -->
</div><!-- .favorites-display-settings -->

<h3><?php _e('Favorite Button Loading Indication', 'favorites'); ?></h3>
<div class="simple-favorites-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplefavorites_display[loadingindicator][include]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicator() ) echo 'checked'; ?> data-favorites-posttype-checkbox />
		</div>
		<div class="post-type-name">
			<?php _e('Display loading indicator for buttons', 'favorites'); ?> <em>(<?php _e('Helpful for slow sites with cache enabled', 'favorites'); ?>)</em>
			<button class="button" data-favorites-toggle-post-type-settings <?php if ( !$display ) echo 'style="display:none;"';?>><?php _e('Settings', 'favorites'); ?></button>
		</div>
		<div class="post-type-settings">
			<div class="row">
				<div class="description">
					<h5><?php _e('Loading Text', 'favorites') ?></h5>
					<p><?php _e('Replaces the unfavorited/favorited button text during the loading state.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<label class="block"><?php _e('Loading Text/HTML', 'favorites'); ?></label>
					<input type="text" name="simplefavorites_display[loadingindicator][text]" value="<?php echo $this->settings_repo->loadingText(); ?>" />
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Loading Spinner', 'favorites') ?></h5>
					<p><?php _e('Adds a spinner to the button during loading state. See plugin documentation for filters available for theme customization.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<label class="block"><input type="checkbox" name="simplefavorites_display[loadingindicator][include_html]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_html') ) echo 'checked'; ?> data-favorites-spinner-type="html">					<?php _e('Use CSS/HTML Spinner', 'favorites'); ?>
					</label>
					<label><input type="checkbox" name="simplefavorites_display[loadingindicator][include_image]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_image') ) echo 'checked'; ?> data-favorites-spinner-type="image">					<?php _e('Use Image/GIF Spinner', 'favorites'); ?>
					</label>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Page Load', 'favorites') ?></h5>
					<p><?php _e('Adds the loading state to favorites buttons during page load. Helpful on sites with page cache enabled.', 'favorites'); ?></p>
				</div>
				<div class="field">
					<label><input type="checkbox" name="simplefavorites_display[loadingindicator][include_preload]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicatorPreload() ) echo 'checked'; ?>><?php _e('Add loading state on page load', 'favorites'); ?>
				</label>
				</div>
			</div><!-- .row -->
		</div><!-- .post-type-settings -->
	</div><!-- .post-type-row -->
</div><!-- .simple-favorites-post-types -->

<h3><?php _e('Additional Display Settings', 'favorites'); ?></h3>
<div class="simple-favorites-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Clear Favorites Button Text', 'favorites'); ?></h5>
			<p><?php _e('The text that appears in the "Clear Favorites" button by default. Note, the text may be overridden in the shortcode or function call.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Clear Favorites Button Text/HTML', 'favorites'); ?></label>
			<input type="text" name="simplefavorites_display[clearfavorites]" value="<?php echo $this->settings_repo->clearFavoritesText(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('No Favorites Text', 'favorites'); ?></h5>
			<p><?php _e('Appears in user favorite lists when they have not favorited any posts.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('No Favorites Text/HTML', 'favorites'); ?></label>
			<input type="text" name="simplefavorites_display[nofavorites]" value="<?php echo $this->settings_repo->noFavoritesText(); ?>" />
		</div>
	</div><!-- .row -->
</div><!-- .favorites-display-settings -->