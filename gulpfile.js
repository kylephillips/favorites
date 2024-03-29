var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var autoprefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var minifycss = require('gulp-minify-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Paths
var scss = 'assets/scss/**/*';
var css = 'assets/css/';

var js_admin_source = [
	'assets/js/src/lib/attrchange.js',
	'assets/js/src-admin/favorites-admin.settings.js',
	'assets/js/src-admin/favorites-admin.listing-customizer.js',
	'assets/js/src-admin/favorites-admin.factory.js'
];

var js_frontend_source = [
	'assets/js/src/favorites.utilities.js',
	'assets/js/src/favorites.formatter.js',
	'assets/js/src/favorites.button-options-formatter.js',
	'assets/js/src/favorites.user-favorites.js',
	'assets/js/src/favorites.clear.js',
	'assets/js/src/favorites.lists.js',
	'assets/js/src/favorites.button.js',
	'assets/js/src/favorites.button-updater.js',
	'assets/js/src/favorites.total-count.js',
	'assets/js/src/favorites.post-favorite-count.js',
	'assets/js/src/favorites.require-authentication.js',
	'assets/js/src/favorites.require-consent.js',
	'assets/js/src/favorites.factory.js'
];

var js_compiled = 'assets/js/';

/**
* Minify the styles and output
*/
var styles = function(){
	return gulp.src(scss)
		.pipe(sass({sourceComments: 'map', sourceMap: 'sass', style: 'compact'}))
		.pipe(autoprefix('last 5 version'))
		.pipe(minifycss({keepBreaks: false}))
		.pipe(gulp.dest(css))
		.pipe(livereload());
}

/**
* Uncompressed styles for development
*/
var uncompressed_styles = function(){
	return gulp.src(scss)
		.pipe(sass({sourceComments: 'map', sourceMap: 'sass', style: 'expanded'}))
		.pipe(autoprefix('last 5 version'))
		.pipe(rename('styles-uncompressed.css'))
		.pipe(gulp.dest(css))
		.pipe(livereload());
}

/**
* Concatenate and minify admin scripts
*/
var admin_scripts = function(){
	return gulp.src(js_admin_source)
		.pipe(concat('favorites-admin.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(js_compiled));
};

/**
* Concatenate and minify front end scripts
*/
var frontend_scripts = function(){
	return gulp.src(js_frontend_source)
		.pipe(concat('favorites.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(js_compiled));
};

/**
* Concatenate and minify front end scripts
*/
var frontend_scripts_pretty = function(){
	return gulp.src(js_frontend_source)
		.pipe(concat('favorites.js'))
		.pipe(gulp.dest(js_compiled));
};

/**
* Watch Task
*/
gulp.task('watch', function(){
	livereload.listen();
	gulp.watch(scss, gulp.series(styles, uncompressed_styles));
	gulp.watch(js_admin_source, gulp.series(admin_scripts));
	gulp.watch(js_frontend_source, gulp.series(frontend_scripts, frontend_scripts_pretty));
});


/**
* Default
*/
gulp.task('default', gulp.series(styles, uncompressed_styles, admin_scripts, frontend_scripts, frontend_scripts_pretty,  'watch'));