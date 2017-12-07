var gulp = require('gulp');
var less = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var notify = require("gulp-notify");
var minifyCSS = require('gulp-minify-css');
var uglify = require('gulp-uglify');

var path = './resources/assets/less/ibl/';
var jsPath = './resources/assets/js/';

gulp.task('bootstrap', function(){
    gulp.src('./resources/assets/less/ibl/bootstrap/bootstrap.less')
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ['last 15 versions', , 'ie 9'],
        }))
        .pipe(minifyCSS())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('less', function(){
    gulp.src([
            path + 'style.less',
            path + 'home.less',
            path + 'login.less',
            path + 'dashboard.less',
            path + 'lesson.less',
            path + 'courses.less',
            path + 'manager.less',
            path + 'profile.less',
            path + 'company.less',
            path + 'tinymce.less',
            path + 'test.less'
        ])
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ['last 15 versions', , 'ie 9'],
        }))
        .pipe(minifyCSS())
        .pipe(gulp.dest('./public/css'));
    gulp.src(path + 'style.less');

});

gulp.task('js_min', function() {
        gulp.src(jsPath + '**/*.js')
        // .pipe(uglify({mangle : false}))
        .pipe(gulp.dest('./public/js'));

        gulp.src(jsPath + 'main.js');
});

gulp.task('js:watch', function(){
    gulp.watch('./resources/assets/js/**/*', ['js_min']);
});

gulp.task('css:watch', function(){
    gulp.watch('./resources/assets/less/**/*', ['less']);
});

gulp.task('default', ['css:watch', 'js:watch']);
