<?php settings_fields( 'simple-favorites-display' ); ?>
<tr valign="top">
	<th scope="row"><?php _e('Enabled Post Types', 'simplefavorites'); ?></th>
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
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][before_content]" value="true" <?php if ( isset($display['before_content']) ) echo ' checked'; ?>/> <?php _e('Insert Before Content', 'simplefavorites') ?>
				</label>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][after_content]" value="true" <?php if ( isset($display['after_content']) ) echo ' checked'; ?>/> <?php _e('Insert After Content', 'simplefavorites') ?>
				</label>
				<label>
					<input type="checkbox" name="simplefavorites_display[posttypes][<?php echo $posttype; ?>][postmeta]" value="true" <?php if ( isset($display['postmeta']) ) echo ' checked'; ?>/> <?php _e('Show Favorite Count on Post Entry Screen', 'simplefavorites') ?>
				</label>
			</div>
		</div>
		<?php endforeach; ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Button Text', 'simplefavorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[buttontext]" value="<?php echo $this->settings_repo->buttonText(); ?>" />
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Button Text (Favorited)', 'simplefavorites'); ?>*</th>
	<td>
		<input type="text" name="simplefavorites_display[buttontextfavorited]" value="<?php echo $this->settings_repo->buttonTextFavorited(); ?>" />
	</td>
</tr>
<tr valign="top">
	<td colspan="2" style="padding:0;"><em style="font-size:13px;">*<?php _e('May contain HTML', 'simplefavorites'); ?></em></td>
</tr>