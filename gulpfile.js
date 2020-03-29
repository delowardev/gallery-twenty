const { src, dest, series } = require('gulp');
const zip = require('gulp-zip');
const clean = require('gulp-clean');

function cleanBuild() {
    return src('./build', {read: false, allowEmpty: true})
        .pipe(clean());
}

function cleanZip() {
    return src('./gallery-twenty.zip', {read: false, allowEmpty: true})
        .pipe(clean());
}

function makeBuild() {
    return src([
        './**',
        '!./build/**',
        '!./.sass-cache/**',
        '!./.DS_Store',
        '!./**/.DS_Store',
        '!./node_modules/**',
        '!./.git',
        '!./dist/**',
        '!./assets/scss/**',
        '!./.gitignore',
        '!./gulpfile.js',
        '!./style.css.map',
        '!./style.scss',
        '!./package.json',
        '!./package-lock.json',
        '!./yarn.lock'
    ]).pipe(dest('build/gallery-twenty/'));
}

function makeZip() {
    return src('./build/**/**')
        .pipe(zip('gallery-twenty.zip'))
        .pipe(dest('./build/'))
}

exports.default = series(cleanBuild, cleanZip, makeBuild, makeZip);
