var gulp = require('gulp');
var elixir = require('laravel-elixir');
var pug = require('laravel-elixir-pug');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');
gulp.task('compress', () => {
  return gulp.src([
    'resource/css/main.css',
    'resource/css/aqua.css',
    'resource/css/blue.css',
    'resource/css/orange.css'
  ]).pipe(cleanCSS())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('resource/css'));
});
elixir.config.sourcemaps = true;
elixir(function (mix) {
  mix.sass('./resource/scss/main.scss', 'resource/css')
    .sass('./resource/scss/aqua.scss', 'resource/css')
    .sass('./resource/scss/blue.scss', 'resource/css')
    .sass('./resource/scss/orange.scss', 'resource/css')
    .pug({
      // Compile to blade.php files or html files
      blade: false,
      // Pretty output or uglified
      pretty: true,
      // Source of pug files
      src: 'resource/pug/',
      // Files to look for, useful if you are still naming files .jade
      search: '**/*.pug',
      exclude: 'resource/pug/includes/**/*',
      // Extension of pug files. Only needed to be set if still naming file .jade
      pugExtension: '.pug',
      // If blade is true, output to resources/views, otherwise public/html
      dest: 'resource/',
      // Any additional watches
      additional_watches: []
    })
    .task('compress')
    .copy('resource/css', 'build/html/css')
    .copy('resource/js', 'build/html/js')
    .copy('resource/images', 'build/html/images')
    .copy('resource/fonts', 'build/html/fonts')
    .copy('resource/documentation', 'build/documentation')
    .copy('resource/scss', 'build/html/scss')
    .copy(['resource/ajaxmail.php', 'resource/subscribemail.php', 'resource/green.html', 'resource/orange.html', 'resource/aqua.html', 'resource/blue.html', 'resource/favicon.ico', 'gulpfile.js', 'package.json'], 'build/html');

  // if (elixir.config.production) {
  //   mix.copy('resource/css', 'build/html/css')
  //     .copy('resource/js', 'build/html/js')
  //     .copy('resource/images', 'build/html/images')
  //     .copy('resource/fonts', 'build/html/fonts')
  //     .copy('resource/documentation', 'build/documentation')
  //     .copy('resource/scss', 'build/html/scss')
  //     .copy(['resource/ajaxmail.php', 'resource/subscribemail.php', 'resource/green.html', 'resource/orange.html', 'resource/aqua.html', 'resource/blue.html', 'resource/favicon.ico', 'gulpfile.js', 'package.json'], 'build/html');
  // }

});

