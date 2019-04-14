const gulp = require('gulp');
const sass = require('gulp-sass');
const del = require("del");
const combine = require('gulp-scss-combine');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const browsersync = require("browser-sync").create();


function browserSync(done) {
    browsersync.init({
        port: 3000
    });
    done();
}

function browserSyncReload(done) {
    browsersync.reload();
    done();
}


function clean() {
    return del(["public/css"]);
}


function css() {
    return gulp
        .src("resources/sass/*.scss")
        .pipe(combine())
        .pipe(sass({ outputStyle: "expanded" }))
        .pipe(concat('main.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest("public/css/"))
        .pipe(browsersync.stream());
}

function watchFiles() {
    gulp.watch("resources/sass/*.scss", css);
}


const build = gulp.series(clean, gulp.parallel(css));
const watch = gulp.parallel(watchFiles, browserSync);


exports.css = css;
exports.clean = clean;
exports.build = build;
exports.watch = watch;
exports.default = build;