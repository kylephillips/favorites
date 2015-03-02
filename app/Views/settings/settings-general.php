<?php settings_fields( 'simple-favorites-general' ); ?>
<tr valign="top">
	<th scope="row"><?php echo $this->plugin_name . ' '; _e('Version', 'simplefavorites'); ?></th>
	<td><strong><?php echo SimpleFavorites\Helpers::version(); ?></strong></td>
</tr>
<tr valign="top">
	<th scope="row"><?php _e('Depenedencies', 'simplefavorites'); ?></th>
	<td>
		<div class="simplefavorites-dependency">
			<label>
				<input type="checkbox" name="simplefavorites_dependencies[css]" value="true" class="simplefavorites-dependency-cb" <?php if ( $this->settings_repo->outputDependency('css') ) echo 'checked'; ?> />
				<?php _e('Output Plugin CSS', 'simplefavorites'); ?>
			</label>
			<div class="simplefavorites-dependency-content">
				<p><em><?php _e('If you are compiling your own minified CSS, include the CSS below:', 'simplefavorites'); ?></em></p>
				<textarea><?php echo SimpleFavorites\Helpers::getFileContents('assets/css/styles-uncompressed.css'); ?></textarea>
			</div>
		</div>

		<div class="simplefavorites-dependency">
			<label>
				<input type="checkbox" name="simplefavorites_dependencies[js]" value="true" class="simplefavorites-dependency-cb" <?php if ( $this->settings_repo->outputDependency('js') ) echo 'checked'; ?> />
				<?php _e('Output Plugin JavaScript', 'simplefavorites'); ?>
			</label>
			<div class="simplefavorites-dependency-content">
				<p><em><?php _e('If you are compiling your own minified Javascript, include the below (required for plugin functionality):', 'simplefavorites'); ?></em></p>
				<textarea><?php echo SimpleFavorites\Helpers::getFileContents('assets/js/src/simple-favorites.js'); ?></textarea>
			</div>
		</div>
	</td>
</tr>