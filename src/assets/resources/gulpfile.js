var paths = {
    'SOURCE': './',
    'DESTINATION': '../',
    'NODE': './node_modules/',
}

// Defining requirements
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var cssnano = require('gulp-cssnano');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var include = require("gulp-include");
var rename = require('gulp-rename');

var sassOptions = {
    errLogToConsole: true,
    outputStyle: 'compressed'
};

gulp.task('compile', function () {

    gulp.src(paths.SOURCE + 'sass/toa-contact-form-styles.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init()) // Start Sourcemaps
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest( paths.DESTINATION + 'css/' ))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(cssnano())
        .pipe(sourcemaps.write('.')) // Creates sourcemaps for minified styles
        .pipe(gulp.dest( paths.DESTINATION + 'css/' ));

    gulp.src([
        paths.NODE + 'jquery-validation/dist/jquery.validate.js',
        paths.SOURCE + 'js/form-validation.js'
    ])
        .pipe(concat('toa-contact-form-scripts.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.DESTINATION + 'js/'));
});


// Loop scan directory for changes
gulp.task('watch', function() {

    gulp.start('compile');

    gulp.watch(paths.SOURCE + 'sass/toa-contact-form-styles.scss', ['compile']).on('change', function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });


    gulp.watch(paths.SOURCE + 'js/*.js', ['compile']).on('change', function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });

});



gulp.task('default', ['watch']);
