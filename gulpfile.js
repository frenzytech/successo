const { src, dest, parallel } = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const clean = require('gulp-clean');
const stripdebug = require('gulp-strip-debug');
const uglify = require('gulp-uglify');
const less = require('gulp-less');
const minifyCSS = require('gulp-csso');
const concat = require('gulp-concat');
const autoprefixer = require('gulp-autoprefixer');


function css_common() {
  return src('./assets/less/common.less')
    .pipe(less())
    .pipe(minifyCSS())
    .pipe(sourcemaps.write('./maps'))
    .pipe(autoprefixer({
        cascade: false
    }))
    .pipe(dest('dist/assets/css'))
}

function css_public() {
  return src('./assets/less/public.less')
    .pipe(less())
    .pipe(minifyCSS())
    .pipe(sourcemaps.write('./maps'))
    .pipe(autoprefixer({
        cascade: false
    }))
    .pipe(dest('dist/assets/css'))
}

function css_admin() {
  return src('./assets/less/admin.less')
    .pipe(less())
    .pipe(minifyCSS())
    .pipe(sourcemaps.write('./maps'))
    .pipe(autoprefixer({
        cascade: false
    }))
    .pipe(dest('dist/assets/css'))
}

function js_common() {
  return src('./assets/js/common/*.js', { sourcemaps: true })
    .pipe(concat('common.min.js'))
    .pipe(dest('dist/assets/js', { sourcemaps: true }))
}


function js_public() {
  return src('./assets/js/public/*.js', { sourcemaps: true })
    .pipe(concat('public.min.js'))
    .pipe(dest('dist/assets/js', { sourcemaps: true }))
}

function js_admin() {
  return src('./assets/js/admin/*.js', { sourcemaps: true })
    .pipe(concat('admin.min.js'))
    .pipe(dest('dist/assets/js', { sourcemaps: true }))
}

function clean_dist() {
  return src(['dist/*'], {read:false})
  .pipe(clean());
}

var filesToMove = [
        './assets/fonts/*.*',
        './assets/images/*.*',
        './assets/images/*/*.*',
        './assets/css/*.*',
        './assets/doc/*.*',
        './assets/html/*.*',
//        './node_modules/fancybox/dist/css/*.*',
//        './node_modules/fancybox/dist/js/*.*',
//        './node_modules/@fortawesome/fontawesome-free/css/all.min.css',
//        './node_modules/@fortawesome/fontawesome-free/js/all.min.js',
//        './node_modules/@fortawesome/fontawesome-free/webfonts/*.*',
//        './node_modules/alertifyjs/build/alertify.min.js',
//        './node_modules/alertifyjs/build/css/alertify.min.css',
//        './node_modules/bootstrap/dist/*/*.*',
//        './node_modules/ckeditor4/**/*',
//       './node_modules/cleave.js/dist/*.*',
//        './node_modules/jquery/dist/*.*',
//        './node_modules/moment/min/*.*',
//        './node_modules/parsleyjs/dist/*.*'
    ];

function move() {
  return src(filesToMove, { base: './' })
  .pipe(dest('dist'));
}

exports.clean = clean_dist;
exports.move = move;
exports.js_common = js_common;
exports.js_public = js_public;
exports.js_admin = js_admin;
exports.css_common = css_common;
exports.css_public = css_public;
exports.css_admin = css_admin;
exports.production = parallel(js_common, js_public, js_admin, css_common, css_public, css_admin, move);
exports.default = parallel(js_common, js_public, js_admin, css_common, css_public, css_admin, move);
exports.js = parallel(js_common, js_public, js_admin);
exports.css = parallel(css_common, css_public, css_admin);