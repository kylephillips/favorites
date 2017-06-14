var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var notify = require('gulp-notify');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Paths
var scss = 'assets/scss/**/*';
var css = 'assets/css/';

var js_admin_source = [
	'assets/js/src/lib/attrchange.js',
	'assets/js/src-admin/favorites-admin.settings.js',
	'assets/js/src-admin/favorites-admin.factory.js'
];

var js_frontend_source = [
	'assets/js/src/favorites.utilities.js',
	'assets/js/src/favorites.formatter.js',
	'assets/js/src/favorites.noncegenerator.js',
	'assets/js/src/favorites.user-favorites.js',
	'assets/js/src/favorites.clear.js',
	'assets/js/src/favorites.lists.js',
	'assets/js/src/favorites.button.js',
	'assets/js/src/favorites.button-updater.js',
	'assets/js/src/favorites.total-count.js',
	'assets/js/src/favorites.require-authentication.js',
	'assets/js/src/favorites.factory.js'
];

var js_compiled = 'assets/js/';

/**
* Smush the admin Styles and output
*/
gulp.task('scss', function(){
	return gulp.src(scss)
		.pipe(sass({ outputStyle: 'compressed' }))
		.pipe(autoprefix('last 15 version'))
		.pipe(gulp.dest(css))
		.pipe(livereload())
		.pipe(notify('Favorites styles compiled & compressed.'));
});

/**
* Uncompressed styles
*/
gulp.task('uncompressed_styles', function(){
	return gulp.src(scss)
		.pipe(sass({ outputStyle: 'expanded' }))
		.pipe(autoprefix('last 15 version'))
		.pipe(rename('styles-uncompressed.css'))
		.pipe(gulp.dest(css))
});

/**
* Admin Scripts
*/
gulp.task('admin_scripts', function(){
	return gulp.src(js_admin_source)
		.pipe(concat('favorites-admin.min.js'))
		.pipe(gulp.dest(js_compiled))
		.pipe(uglify())
		.pipe(gulp.dest(js_compiled))
		.pipe(notify('Favorites admin scripts compiles & compressed.'));
});

/**
* Front end Scripts
*/
gulp.task('frontend_scripts', function(){
	return gulp.src(js_frontend_source)
		.pipe(concat('favorites.min.js'))
		.pipe(gulp.dest(js_compiled))
		.pipe(uglify())
		.pipe(gulp.dest(js_compiled))
		.pipe(notify('Favorites front end scripts compiles & compressed.'));
});

/**
* Front end Scripts - Unminified
*/
gulp.task('frontend_scripts_pretty', function(){
	return gulp.src(js_frontend_source)
		.pipe(concat('favorites.js'))
		.pipe(gulp.dest(js_compiled));
});

/**
* Watch Task
*/
gulp.task('watch', function(){
	livereload.listen();
	gulp.watch(scss, ['scss', 'uncompressed_styles']);
	gulp.watch(js_admin_source, ['admin_scripts']);
	gulp.watch(js_frontend_source, ['frontend_scripts']);
	gulp.watch(js_frontend_source, ['frontend_scripts_pretty']);
});

/**
* Default
*/
gulp.task('default', ['scss', 'uncompressed_styles', 'admin_scripts', 'frontend_scripts', 'frontend_scripts_pretty', 'watch']);