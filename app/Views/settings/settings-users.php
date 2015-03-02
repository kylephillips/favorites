<?php settings_fields( 'simple-favorites-users' ); ?>
<tr valign="top">
	<td colspan="2" style="padding:0;">
		<p style="font-size:14px;font-style:oblique;"><?php _e("Logged in users' favorites are saved in both the selected save type and in the user's meta." ); ?></p>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Anonymous Users', 'simplefavorites'); ?></th>
	<td>
		<label style="display:block;margin-bottom:10px;">
			<input type="checkbox" name="simplefavorites_users[anonymous][display]" value="true" class="simplefavorites-display-anonymous" <?php if ( $this->settings_repo->anonymous('display') ) echo ' checked'; ?> />
			<?php _e('Enable Favorites', 'simplefavorites'); ?>
		</label>
		<label class="simplefavorites-save-anonymous" style="display:none;">
			<input type="checkbox" name="simplefavorites_users[anonymous][save]" value="true" <?php if ( $this->settings_repo->anonymous('save') ) echo ' checked'; ?> />
			<?php _e('Include in Post Favorite Count', 'simplefavorites'); ?>
		</label>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Save Favorites in', 'simplefavorites');?></th>
	<td>
		<label style="display:block;margin-bottom:10px;">
			<input type="radio" name="simplefavorites_users[anonymous][saveas]" value="cookie" <?php if ( $this->settings_repo->saveType() == 'cookie' ) echo 'checked'; ?>/>
			<?php _e('Cookie', 'simplefavorites'); ?>
		</label>
		<label>
			<input type="radio" name="simplefavorites_users[anonymous][saveas]" value="session" <?php if ( $this->settings_repo->saveType() == 'session' ) echo 'checked'; ?>/>
			<?php _e('Session', 'simplefavorites'); ?>
		</label>
	</td>
</tr>