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
			<h5><?php _e('User Cookie Consent', 'favorites'); ?></h5>
			<p><?php _e('Require user consent for saving cookies before allowing favorites to be saved.', 'favorites'); ?></p><p><strong><?php _e('Important:', 'favorites'); ?></strong> <?php _e('If using this option for GDPR compliance, please consult an attorney for appropriate legal terms to display in the modal consent.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplefavorites_users[consent][require]" value="true" <?php if ( $this->settings_repo->consent('require') ) echo ' checked'; ?> data-favorites-require-consent-checkbox /><?php _e('Require User Consent', 'favorites'); ?>
			</label>
			<div class="require-consent-modal-content" data-favorites-require-consent-modal-content>
				<h3><?php _e('Content to Display in Modal Agreement', 'favorites'); ?></h3>
				<?php
					wp_editor($this->settings_repo->consent('modal'), 'simplefavorites_users_authentication_modal', 
					array(
						'textarea_name' => 'simplefavorites_users[consent][modal]',
						'tabindex' => 1,
						'wpautop' => true
						)
					); 
				?>
				<p>
					<label class="block"><?php _e('Consent Button Text', 'favorites'); ?></label>
					<input type="text" name="simplefavorites_users[consent][consent_button_text]" value="<?php echo $this->settings_repo->consent('consent_button_text'); ?>" />
				</p>
				<p>
					<label class="block"><?php _e('Deny Button Text', 'favorites'); ?></label>
					<input type="text" name="simplefavorites_users[consent][deny_button_text]" value="<?php echo $this->settings_repo->consent('deny_button_text'); ?>" />
				</p>
			</div>
		</div>
	</div><!-- .row -->
	<div class="row" data-favorites-require-login>
		<div class="description">
			<h5><?php _e('Anonymous Favoriting Behavior', 'favorites'); ?></h5>
			<p><?php _e('By default, favorite buttons are hidden from unauthenticated users if anonymous users are disabled.', 'favorites'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplefavorites_users[require_login]" value="true" <?php if ( $this->settings_repo->requireLogin() ) echo ' checked'; ?> data-favorites-require-login-checkbox data-favorites-anonymous-settings="modal" /><?php _e('Show Buttons and Display Modal for Anonymous Users', 'favorites'); ?>
			</label>
			<label class="block"><input type="checkbox" name="simplefavorites_users[redirect_anonymous]" value="true" <?php if ( $this->settings_repo->redirectAnonymous() ) echo ' checked'; ?> data-favorites-redirect-anonymous-checkbox data-favorites-anonymous-settings="redirect" /><?php _e('Redirect Anonymous Users to a Page', 'favorites'); ?>
			</label>
			<div class="authentication-modal-content" data-favorites-authentication-modal-content>
				<h3><?php _e('Edit the Modal Content Below', 'favorites'); ?></h3>
				<p><strong><?php _e('Important: ', 'favorites'); ?></strong> <?php _e('If plugin css or javascript has been disabled, the modal window will not display correctly.', 'favorites'); ?></p>
				<p><?php _e('To add "close" button or link, give it a data attribute of "data-favorites-modal-close".', 'favorites'); ?></p>
				<?php
					wp_editor($this->settings_repo->authenticationModalContent(true), 'simplefavorites_users_authentication_modal', 
					array(
						'textarea_name' => 'simplefavorites_users[authentication_modal]',
						'tabindex' => 1,
						'wpautop' => true
						)
					); 
				?>
			</div>
			<div class="anonymous-redirect-content" data-favorites-anonymous-redirect-content>
				<label><?php _e('Enter the Page/Post ID to redirect to (defaults to the site url)', 'sscblog'); ?></label>
				<input type="text" name="simplefavorites_users[anonymous_redirect_id]" value="<?php echo $this->settings_repo->redirectAnonymousId(); ?>" />
			</div>
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