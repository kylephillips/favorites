<?php settings_fields( 'simple-favorites-users' ); ?>

<h3><?php _e('User Settings', 'favorites'); ?></h3>
<div class="simple-favorites-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Anonymous Users', 'favorites'); ?></h5>
			<p><?php _e('Enable favoriting functionality for unauthenticated users.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplefavorites_users[anonymous][display]" value="true" <?php if ( $this->settings_repo->anonymous('display') ) echo ' checked'; ?> data-favorites-anonymous-checkbox /><?php _e('Enable Anonymous Users', 'favorites'); ?>
			</label>
			<label class="block" data-favorites-anonymous-count><input type="checkbox" name="simplefavorites_users[anonymous][save]" value="true" <?php if ( $this->settings_repo->anonymous('save') ) echo ' checked'; ?> /><?php _e('Include in Post Favorite Count', 'favorites'); ?>
			</label>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Save Unauthenticated Favorites as', 'favorites'); ?></h5>
			<p><?php _e('Unauthenticated users\' favorites may be saved in either cookies or session. Authenticated users\' favorites are saved as user meta.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="radio" name="simplefavorites_users[anonymous][saveas]" value="cookie" <?php if ( $this->settings_repo->saveType() == 'cookie' ) echo 'checked'; ?>/><?php _e('Cookie', 'favorites'); ?>
			</label>
			<label>
				<input type="radio" name="simplefavorites_users[anonymous][saveas]" value="session" <?php if ( $this->settings_repo->saveType() == 'session' ) echo 'checked'; ?>/><?php _e('Session', 'favorites'); ?>
			</label>
		</div>
	</div><!-- .row -->
</div><!-- .favorites-display-settings -->