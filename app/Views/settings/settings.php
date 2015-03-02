<div class="wrap">
	<h1><?php echo $this->plugin_name . ' '; _e('Settings', 'simplefavorites'); ?></h1>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( $tab == 'general' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-favorites">
			<?php _e('General', 'simplefavorites'); ?>
		</a>
		<a class="nav-tab <?php if ( $tab == 'users' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-favorites&tab=users">
			<?php _e('Users', 'simplefavorites'); ?>
		</a>
		<a class="nav-tab <?php if ( $tab == 'display' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-favorites&tab=display">
			<?php _e('Display', 'simplefavorites'); ?>
		</a>
	</h2>

	<form method="post" enctype="multipart/form-data" action="options.php">
		<table class="form-table">
			<?php include(SimpleFavorites\Helpers::view('settings/settings-' . $tab)); ?>
		</table>
		<?php submit_button(); ?>
	</form>
</div><!-- .wrap -->