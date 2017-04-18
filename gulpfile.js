var gulp = require('gulp');
    pug  = require('gulp-pug');
    del = require('del');
    // sass = require('gulp-sass');
    compass = require('gulp-compass');
    minifyCSS = require('gulp-minify-css');
    autoprefixer = require('gulp-autoprefixer'),
    //jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    connect = require('gulp-connect'),
    sourcemaps = require('gulp-sourcemaps'),
    livereload = require('gulp-livereload');
    gulpPlumber = require('gulp-plumber');



//*******************************
//Sass compile
//*******************************
var _sassDiskPath = './src/sass/*';
var _cssDestPath = './dist/style/';
var AUTOPREFIXER_BROWSERS = [
        '> 1%',
        'last 2 versions',
        'firefox >= 4',
        'safari 7',
        'safari 8',
        'IE 8',
        'IE 9',
        'IE 10',
        'IE 11'
];

gulp.task('styles', function () {
    return gulp.src(_sassDiskPath) // sass 來源路徑
    .pipe(compass({
        project: __dirname,
        css: './dist/style/', // compass 輸出位置
        sass: './src/sass/',      // sass 來源路徑
        style: 'nested',  // CSS 處理方式，預設 nested（expanded, nested, compact, compressed）
        comments: false,      // 是否要註解，預設(true)
        logging  : false,
        sourcemap: true
    }))
    // .pipe(autoprefixer({AUTOPREFIXER_BROWSERS}))
    .pipe(gulp.dest(_cssDestPath)) // 輸出位置
    .pipe(rename({suffix: '.min'}))
    .pipe(minifyCSS({}))
    .pipe(gulp.dest(_cssDestPath))
    .pipe(connect.reload())
    .pipe(notify({ message: 'Styles task complete' }));
});

//*******************************
//Scripts compile
//*******************************

var _jsLibDiskPath = './src/script/lib/*.js';
var _jsDestPath = './dist/js/';

gulp.task('concatJs', function (){
  return gulp.src(_jsLibDiskPath)
      .pipe(gulpPlumber({
        errorHandler: function (error) {
          console.log(error.message);
          this.emit('end');
    }}))
      .pipe(sourcemaps.init())
      //.pipe(jshint())
      //.pipe(jshint.reporter('jshint-stylish'))
      .pipe(concat('concat.js'))
      .pipe(gulp.dest(_jsDestPath))
      .pipe(sourcemaps.write())
      .pipe(rename({suffix: '.min'}))
      .pipe(uglify())
      .pipe(gulp.dest(_jsDestPath))
      .pipe(connect.reload())
      .pipe(notify({ message: 'Scripts task complete' }))

});

var _jsDiskPath = './src/script/*.js';

gulp.task('scripts', function (){
  return gulp.src(_jsDiskPath)
      .pipe(gulpPlumber({
        errorHandler: function (error) {
          console.log(error.message);
          this.emit('end');
    }}))
      //.pipe(jshint())
      //.pipe(jshint.reporter('jshint-stylish'))
      .pipe(gulp.dest(_jsDestPath))
      .pipe(rename({suffix: '.min'}))
      .pipe(uglify())
      .pipe(gulp.dest(_jsDestPath))
      .pipe(connect.reload())
      .pipe(notify({ message: 'Scripts task complete' }))

});

//*******************************
//Html compile
//*******************************

var _htmlDiskPath = './src/pug/*.pug';
var _htmlDestPath = './dist/';


gulp.task('html', function () {
  return gulp.src(_htmlDiskPath)
      .pipe(gulpPlumber({
        errorHandler: function (error) {
          console.log(error.message);
          this.emit('end');
    }}))
      .pipe(pug())
      .pipe(gulp.dest(_htmlDestPath))
      .pipe(connect.reload())
      .pipe(notify({ message: 'html task complete' }));
});

//*******************************
//圖片壓縮
//*******************************


var _imagesDiskPath = './src/images/**';
var _imagesDestPath = './dist/images';

gulp.task('images', function() { 
  return gulp.src(_imagesDiskPath )
    .pipe(cache(imagemin({ 
      optimizationLevel: 3, 
      progressive: true, 
      interlaced: true 
    })))
    .pipe(gulp.dest(_imagesDestPath))
    // .pipe(notify({ message: 'Images task complete' }))

});


// *********************
// LiveReload
// *********************
gulp.task('connect', function () {
    connect.server({
      livereload: true
    });
});

gulp.task('watch', function() {
       gulp.watch(_sassDiskPath, ['styles']);
       gulp.watch('./src/sass/*/*.scss', ['styles']);
       gulp.watch(_jsDiskPath, ['scripts']);
       gulp.watch('./src/script/*/*.js', ['concatJs']);
       gulp.watch(_imagesDiskPath, ['images']);
});

//*******************************
//清除目的地目錄並重建檔案
//*******************************

gulp.task('clean', function(cb) { 
    return del(['./dist/js/*','./dist/style/*'] ,cb);
});

gulp.task('default',['clean'],function (){
    gulp.start('styles','concatJs','scripts','connect','watch');
});



