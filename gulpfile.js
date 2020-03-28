const { src, dest, series } = require('gulp');
const zip = require('gulp-zip');
const replace = require('gulp-replace');
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
        './*',
        '!**/.DS_Store',
        '!**/.__MACOSX',
        '!./build/*',
        '!./.git/*',
        '!./.gitignore',
        '!./.stylelintrc.json',
        '!./.sass-cache',
        '!./style.css.map',
        '!./assets/scss/*',
        '!./node_modules/*',
        '!./**/*.zip',
        '!./gulpfile.js',
        '!./readme.md',
        '!./LICENSE.txt',
        '!./package.json',
        '!./package-lock.json',
    ]).pipe(dest('build/gallery-twenty/'));
}

function makeZip() {
    return src('./build/**/*')
        .pipe(zip('gallery-twenty.zip'))
        .pipe(dest('./build/'))
}
