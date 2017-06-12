<?php settings_fields( 'simple-favorites-display' ); ?>
<tr valign="top">
	<th scope="row"><?php _e('Enabled Post Types', 'favorites'); ?></th>
	<td>
		<?php 
		foreach ( $this->post_type_repo->getAllPostTypes() as $posttype ) : 
			$display = $this->settings_repo->displayInPostType($posttype);
		?>
		<div class="simple-favorites-posttype">
			<label style="display:block;margin-bottom:5px;">
				<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][display]" value="true" <?php if ( $display ) echo ' checked'; ?> data-sf-posttype /> <?php echo $posttype; ?>
			</label>
			<div class="simple-favorites-posttype-locations" <?php if ( $display ) echo ' style="display:block;"'; ?>>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][before_content]" value="true" <?php if ( isset($display['before_content']) ) echo ' checked'; ?>/> <?php _e('Insert Before Content', 'favorites') ?>
				</label>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][after_content]" value="true" <?php if ( isset($display['after_content']) ) echo ' checked'; ?>/> <?php _e('Insert After Content', 'favorites') ?>
				</label>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][postmeta]" value="true" <?php if ( isset($display['postmeta']) ) echo ' checked'; ?>/> <?php _e('Show Favorite Count on Post Entry Screen', 'favorites') ?>
				</label>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][admincolumns]" value="true" <?php if ( isset($display['admincolumns']) ) echo ' checked'; ?>/> <?php _e('Show Favorite Count in Admin Columns', 'favorites') ?>
				</label>
			</div>
		</div>
		<?php endforeach; ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Button Text', 'favorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[buttontext]" value="<?php echo $this->settings_repo->buttonText(); ?>" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Button Text (Favorited)', 'favorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[buttontextfavorited]" value="<?php echo $this->settings_repo->buttonTextFavorited(); ?>" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Clear Favorites Button Text', 'favorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[clearfavorites]" value="<?php echo $this->settings_repo->clearFavoritesText(); ?>" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Favorite Count', 'favorites'); ?></th>
	<td>
		<label>
			<input type="checkbox" name="simplefavorites_display[buttoncount]" value="true" <?php if ( $this->settings_repo->includeCountInButton() ) echo 'checked'; ?> />
			<?php _e('Include total favorite count in button text', 'favorites'); ?>
		</label>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Loading Indication', 'favorites'); ?></th>
	<td>
		<label>
			<input type="checkbox" class="simplefavorites-display-loading" name="simplefavorites_display[loadingindicator][include]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicator() ) echo 'checked'; ?> />
			<?php _e('Display loading indicator for buttons', 'favorites'); ?>
			<em>(<?php _e('Helpful for slow sites with cache enabled', 'favorites'); ?>)</em>
		</label>
		<div class="simplefavorites-loading-fields" style="padding-top:10px;display:none;">
			<p>
				<label>Loading Text</label><br>
				<input type="text" name="simplefavorites_display[loadingindicator][text]" value="<?php echo $this->settings_repo->loadingText(); ?>" />
			</p>
			<p style="padding-top:10px;">
				<label>
					<input type="checkbox" name="simplefavorites_display[loadingindicator][include_html]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_html') ) echo 'checked'; ?> data-favorites-spinner-type="html">
					<?php _e('Include loading indicator css spinner', 'favorites'); ?>
				</label>
			</p>
			<p style="padding-top:10px;">
				<label>
					<input type="checkbox" name="simplefavorites_display[loadingindicator][include_image]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_image') ) echo 'checked'; ?> data-favorites-spinner-type="image">
					<?php _e('Include loading indicator image', 'favorites'); ?>
				</label>
			</p>
			<p style="padding-top:10px;">
				<label>
					<input type="checkbox" name="simplefavorites_display[loadingindicator][include_preload]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicatorPreload() ) echo 'checked'; ?>>
					<?php _e('Include loading indicator on page load', 'favorites'); ?>
				</label>
			</p>			
		</div>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('No Favorites Text', 'favorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[nofavorites]" value="<?php echo $this->settings_repo->noFavoritesText(); ?>" />
	</td>
</tr>
<tr valign="top">
	<td colspan="2" style="padding:0;"><em style="font-size:13px;">*<?php _e('May contain HTML', 'favorites'); ?></em></td>
</tr>