var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var notify = require('gulp-notify');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var pump = require('pump');

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
	'assets/js/src/favorites.noncegenerator.js',
	'assets/js/src/favorites.user-favorites.js',
	'assets/js/src/favorites.clear.js',
	'assets/js/src/favorites.lists.js',
	'assets/js/src/favorites.button.js',
	'assets/js/src/favorites.button-updater.js',
	'assets/js/src/favorites.total-count.js',
	'assets/js/src/favorites.post-favorite-count.js',
	'assets/js/src/favorites.require-authentication.js',
	'assets/js/src/favorites.factory.js'
];

var js_compiled = 'assets/js/';

/**
* Smush the admin Styles and output
*/
gulp.task('scss', function(callback){
	pump([
		gulp.src(scss),
		sass({ outputStyle: 'compressed' }),
		autoprefix('last 15 version'),
		gulp.dest(css),
		livereload(),
		notify('Favorites styles compiled & compressed.')
	], callback);
});

/**
* Uncompressed styles
*/
gulp.task('uncompressed_styles', function(callback){
	pump([
		gulp.src(scss),
		sass({ outputStyle: 'expanded' }),
		autoprefix('last 15 version'),
		rename('styles-uncompressed.css'),
		gulp.dest(css)
	], callback);
});

/**
* Admin Scripts
*/
gulp.task('admin_scripts', function(callback){
	pump([
		gulp.src(js_admin_source),
		concat('favorites-admin.min.js'),
		gulp.dest(js_compiled),
		uglify(),
		gulp.dest(js_compiled),
		notify('Favorites admin scripts compiles & compressed.')
	], callback);
});

/**
* Front end Scripts
*/
gulp.task('frontend_scripts', function(callback){
	pump([
		gulp.src(js_frontend_source),
		concat('favorites.min.js'),
		gulp.dest(js_compiled),
		uglify(),
		gulp.dest(js_compiled),
		notify('Favorites front end scripts compiles & compressed.')
	], callback);
});

/**
* Front end Scripts - Unminified
*/
gulp.task('frontend_scripts_pretty', function(callback){
	pump([
		gulp.src(js_frontend_source),
		concat('favorites.js'),
		gulp.dest(js_compiled)
	], callback);
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