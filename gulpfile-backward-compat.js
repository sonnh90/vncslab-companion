/** 1. Declare module variables 
 * Search for gulp package in nodejs directory
 * **/
var gulp = require('gulp');
var babel = require('gulp-babel');
var gulpSass = require('gulp-sass')( require('sass') ); // a little weird syntax. a closure ?
var rename = require('gulp-rename');
var autoPrefixer = require('gulp-autoprefixer');
var sourceMaps = require('gulp-sourcemaps');
var browserify = require('browserify');
var babelify = require('babelify');
var vinylSource = require('vinyl-source-stream');
var vinylBuffer = require('vinyl-buffer');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
const vinylSourceStream = require('vinyl-source-stream');
var browserSync = require('browser-sync').create();
var reloadBrowser = browserSync.reload();


/** 2. Variables for relevant Gulp task **/

var resourcesPaths = {
    styles:{
        prelib:{
            srcBootstrapFile:'node_modules/bootstrap/scss/bootstrap.scss',
            srcDir: 'sources/scss/prelib/',
            srcListFile: 'sources/scss/prelib/*.scss',
            destDir: './assets/css/prelib/'
        },
        admin:{
            srcDir: 'sources/scss/admin/',
            srcListFile: 'sources/scss/admin/*.scss',
            destDir: './assets/css/admin/'
        },
        editor:{
            srcDir: 'sources/scss/editor/',
            srcListFile: 'sources/scss/editor/*.scss',
            destDir: './assets/css/editor/'
        },
        frontend:{
            srcDir: 'sources/scss/frontend/',
            srcDirStCarousel: 'sources/scss/frontend/sunset-carousel/',
            srcListFile: 'sources/scss/frontend/*.scss',
            srcListFileStCarousel: 'sources/scss/frontend/sunset-carousel/*.scss',
            destDir: './assets/css/frontend/',
            destDirStCarousel: './assets/css/frontend/sunset-carousel/',
        }
    },
    scripts:{
        prelib:{
            srcDir: 'sources/js/prelib/',
            srcListFile: 'sources/js/prelib/*.js',
            destDir: './assets/js/prelib/'
        },
        admin:{
            srcDir: 'sources/js/admin/',
            srcListFile: 'sources/js/admin/*.js',
            destDir: './assets/js/admin/'
        },
        editor:{
            srcDir: 'sources/js/editor/',
            srcListFile: 'sources/js/editor/*.js',
            destDir: './assets/js/editor/'
        },
        frontend:{
            srcDir: 'sources/js/frontend/',
            srcDirStCarousel: 'sources/js/frontend/sunset-carousel/',
            srcListFile: 'sources/js/frontend/*.js',
            destDir: './assets/js/frontend/',
            destDirStCarousel: './assets/js/frontend/sunset-carousel/',
        }
    }
};


/**
 * ====================================
 * 3. Gulp tasks
 * ====================================
 */

/* 
 * 3.1. Define manual compilation tasks
 * - Compile SCSS/SASS to CSS manually
 * - Compile JS ES6 to JS ES5 manually 
 * **/

/* 3.1.1. Define gulp tasks - 
* - The admin SCSS distribution task to gulp with named function
***/
gulp.task( 'distribute-all-prelib-styles', distributeAllPrelibStyles );
gulp.task( 'distribute-all-admin-styles', distributeAllAdminStyles );
gulp.task( 'distribute-all-editor-styles', distributeAllEditorStyles );
gulp.task( 'distribute-all-frontend-styles', distributeAllFrontendStyles );

/* 3.1.2. Helper functions for gulp task */
// .1. Distribute all prerequisite library styles
function distributeAllPrelibStyles(callback){
    // distribute_all_scss_to_css_beauty_format( prelibStylesSrcListFiles, prelibStylesDistDir ); // OK
    // 1. Distribute all scss from prelib directory
    distribute_all_scss_to_css_beauty_format(
        resourcesPaths.styles.prelib.srcListFile,
        resourcesPaths.styles.prelib.destDir
    );
    
    // 2. Distribute Bootstrap library 
    distribute_single_scss_to_css_beauty_format(
        resourcesPaths.styles.prelib.srcBootstrapFile,
        resourcesPaths.styles.prelib.destDir
    );

    callback();
}

// .2. Distribute all WordPress admin setting pages styles (SCSS to CSS)
function distributeAllAdminStyles(callback){
    // distribute_all_scss_to_css_beauty_format( adminStylesSrcListFiles, adminStylesDistDir ); // OK
    distribute_all_scss_to_css_beauty_format(
        resourcesPaths.styles.admin.srcListFile,
        resourcesPaths.styles.admin.destDir
    );

    callback();
}

function distributeAllEditorStyles(callback){
    // distribute_all_scss_to_css_beauty_format( adminStylesSrcListFiles, adminStylesDistDir ); // OK
    distribute_all_scss_to_css_beauty_format(
        resourcesPaths.styles.editor.srcListFile,
        resourcesPaths.styles.editor.destDir
    );

    callback();
}

// .2.3. Distribute all WordPress frontend Sunset Carousel all formats styles 
function distributeAllFrontendStyles(callback){
    // distribute_all_scss_to_css_beauty_format( frontendStylesSrcListFiles, frontendStylesDistDir );// OK
    distribute_all_scss_to_css_beauty_format(
        resourcesPaths.styles.frontend.srcListFile,
        resourcesPaths.styles.frontend.destDir
    );

    distribute_all_scss_to_css_beauty_format(
        resourcesPaths.styles.frontend.srcListFileStCarousel,
        resourcesPaths.styles.frontend.destDirStCarousel
    );

    callback();
}
 
/** 3.1.2. Helper functions for SCSS/SASS to CSS tasks */

/* Compile SCSS to normal CSS
* - outputStyle: no compress
* - No rename */
function distribute_all_scss_to_css_beauty_format( scssSourceDir, cssDestDir ){
    return gulp.src( scssSourceDir )
        .pipe( plumber() )
        .pipe( sourceMaps.init() )
        .pipe(
            gulpSass(
                {
                includePaths:['node_modules/bootstrap/scss'],
                errorLogToConsole: true
                }
            ).on('error', console.error.bind( console ) )
        )
        .pipe(
            autoPrefixer( { cascade: false } )
        )
        .pipe( sourceMaps.write('./') )
        .pipe( gulp.dest( cssDestDir ) )
        .pipe( browserSync.stream() );
}

function distribute_single_scss_to_css_beauty_format( scrFilePath, cssDestDir){
   /*  return gulp.src( scrFilePath )
    .pipe( gulpSass )
    .pipe( gulp.dest( cssDestDir ) ); */
    return gulp.src( scrFilePath )
        .pipe( plumber() )
        .pipe( sourceMaps.init() )
        .pipe(
            gulpSass(
                {
                includePaths:['node_modules/bootstrap/scss'],
                errorLogToConsole: true
                }
            ).on('error', console.error.bind( console ) )
        )
        .pipe(
            autoPrefixer( { cascade: false } )
        )
        .pipe( 
            rename( 
                function(filePath){ 
                    filePath.basename += "-import"; 
                    filePath.extname = ".css";
                } 
            ) 
        )
        .pipe( sourceMaps.write('./') )
        .pipe( gulp.dest( cssDestDir ) )
        .pipe( browserSync.stream() );
}

function distribute_all_scss_to_minified_css( scssSourceDir, cssDestDir ){
    return gulp.src( scssSourceDir )
      .pipe( plumber() )
      .pipe( sourceMaps.init() )
      .pipe(
        gulpSass(
          {
            includePaths:['node_modules/bootstrap/scss'],
            errorLogToConsole: true,
            outputStyle: 'compressed'
          }
        ).on('error', console.error.bind( console ) )
      )
      .pipe(
        autoPrefixer({ cascade: false })
      )
      .pipe( rename( { suffix: '.min' } ) )
      .pipe( sourceMaps.write('./') )
      .pipe( gulp.dest( cssDestDir ) )
      .pipe( browserSync.stream() );
}

/**3.2. 
 * - Compile SCSS/SASS to minified CSS
 * - Compile JavaScript ES6 to minified Vanilla JavaScript
 * **/
/** 3.2.1. Define compiling JS ES6 gulp tasks**/
gulp.task( 'distribute-all-prelib-scripts', distributeAllPrelibScripts );
gulp.task( 'distribute-all-admin-scripts', distributeAllAdminScripts );
gulp.task( 'distribute-all-editor-scripts', distributeAllEditorScripts );
gulp.task( 'distribute-all-frontend-scripts', distributeAllFrontendScripts );

// gulp.task( 'distribute-bootstrap-prelib-scripts', distributeBootstrapPrelibScripts );

gulp.task( 'distribute-all-prelib-scripts', distributeAllPrelibScripts );
function distributeAllPrelibScripts(callback){
    // Jquery 3.7.0
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.prelib.srcDir,
        'jquery-import.js',
        resourcesPaths.scripts.prelib.destDir
    );

    // Bootstrap 5.3.0
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.prelib.srcDir,
        'bootstrap-import.js',
        resourcesPaths.scripts.prelib.destDir
    );

    callback();
}

/** 3.2.2. Helper functions for compiling JS ES6 gulp tasks
 * - Redefine later. Manually copy first **/
function distributeAllPrelibScripts(callback){
    // Just copy the prelib from source to destination 
    filesList = [ 
        'ace' 
    ];
    /* For multiple file, iterate through the filesList, then copy each single files */
    
    /* input args srcDir, destDir, filesList  */
    /* copy_single_file_from_src_to_dest(
        resourcesPaths.scripts.prelib.srcDir,
        resourcesPaths.scripts.prelib.destDir,
        'devsunsetnew.ace.js' 
    );
 */
    /* 1. Copying the ACE library to the destination folder : */
    let aceLibDirectory = resourcesPaths.scripts.prelib.srcDir + 'ace';
    let ace 

    copy_directory_recursively_from_src_to_dest( 
        aceLibDirectory , 
        resourcesPaths.scripts.prelib.destDir
    );
    
    callback();
}

function distributeAllAdminScripts(callback){
    /* distribute_all_js_to_readable_js(
        resourcesPaths.scripts.admin.srcListFile,
        resourcesPaths.scripts.admin.destDir
    ); */
    
    // General admin page
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.admin.srcDir,
        'admin-parent.js',
        resourcesPaths.scripts.admin.destDir
    );
    
    // // Custom CSS admin subpage
    // distribute_single_js_to_vanilla_js(
    //     resourcesPaths.scripts.admin.srcDir,
    //     'custom-css-subpage.js',
    //     resourcesPaths.scripts.admin.destDir
    // );
    
    // Sunset carousel admin subpage
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.admin.srcDir,
        'sunset-carousel-subpage.js',
        resourcesPaths.scripts.admin.destDir
    );

    callback();
}

function distributeAllEditorScripts(callback){
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.editor.srcDir,
        'sunsetpro-carousel-cpt.js',
        resourcesPaths.scripts.editor.destDir
    );

    callback();
}

function distributeAllFrontendScripts(callback){
    // 1. Scripts for default post type

    // 1.1. Scripts for "page" default post type 
    // 1.1.1. Front page
    /* distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'front-page.js',
        resourcesPaths.scripts.frontend.destDir
    );
    
    // 1.1.2. Blog page
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'blog-page.js',
        resourcesPaths.scripts.frontend.destDir
    );

    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'page-full-content.js',
        resourcesPaths.scripts.frontend.destDir
    );   */  
    
    // Scripts for both "post" and "page"
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'general-page.js',
        resourcesPaths.scripts.frontend.destDir
    );

    // 1.2. Scripts for "post" default post type 
    /* distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'post-full-content.js',
        resourcesPaths.scripts.frontend.destDir
    );

    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'post-format-all.js',
        resourcesPaths.scripts.frontend.destDir
    );

    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'post-format-gallery.js',
        resourcesPaths.scripts.frontend.destDir
    );

    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'post-format-aside.js',
        resourcesPaths.scripts.frontend.destDir
    ); */

    // 1.3. Script for stcarousel CPT (Sunset Carousel custom post type)
    
    // 1.4. Scripts for cutom content block
    // 1.4.1. Scripts for Sunset Carousel custom post type
    // Bootstrap Sunset carousel standard block
    // This script includes standard-block & classic-block scripts
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'sunset-carousel-general-block.js',
        resourcesPaths.scripts.frontend.destDir
    );

    distribute_single_js_to_vanilla_js(
         resourcesPaths.scripts.frontend.srcDirStCarousel,
         'sunset-carousel-standard-block.js',
         resourcesPaths.scripts.frontend.destDirStCarousel
    );

    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDirStCarousel,
        'sunset-carousel-classic-block.js',
        resourcesPaths.scripts.frontend.destDirStCarousel
    );

    // 1.5. Script for custom templates 
    // 1.5.1. Scripts for custom page templates
    distribute_single_js_to_vanilla_js(
        resourcesPaths.scripts.frontend.srcDir,
        'sunsetpro-template-all.js',
        resourcesPaths.scripts.frontend.destDir
    );

    callback();
}

/** 3.2.3. Further helper functions **/


/* This method does not work ??? */
/* function distribute_all_js_to_readable_js(jsSourceDir, jsDestDir){
    return gulp.src(jsSourceDir)
      .pipe( plumber() )
      .pipe( babel() )
      .pipe( rename({ extname: ".js" }) )
      .pipe( vinylBuffer() )
      .pipe( sourceMaps.init({ loadMaps: true }) )
      .pipe( sourceMaps.write( './' ) )
      .pipe( gulp.dest( jsDestDir ) )
      .pipe( browserSync.stream() );
} */

function distribute_single_js_to_vanilla_js(jsFileDir, jsFileName, jsDestDir) {
    return browserify({
      entries: [jsFileDir + jsFileName]
    })
      .transform( babelify, {presets: ["@babel/preset-env"] })
      .bundle()
      .pipe( vinylSource( jsFileName ) )
      .pipe( plumber() )
      .pipe( rename({ extname: ".js" }) )
      .pipe( vinylBuffer() )
      .pipe( sourceMaps.init({ loadMaps: true }) )
      .pipe( sourceMaps.write( './' ) )
      .pipe( gulp.dest( jsDestDir ) )
      .pipe( browserSync.stream() );
}

/*  */
function copy_single_file_from_src_to_dest( srcDir, destDir, fileName ){
    let filePath = srcDir + fileName;

    return gulp.src( filePath )
        .pipe( gulp.dest( destDir ) );
}

function copy_directory_recursively_from_src_to_dest( srcDir, destDir){
    let tmpSrcDir = srcDir + '**/*';

    return gulp.src( tmpSrcDir )
        .pipe( gulp.dest( destDir ) );
}

/* function copy_files_from_src_to_dest( destDir, relativeFilesPathList ){
    return gulp.src( relativeFilesPathList , { base: './'})
        .pipe( gulp.dest( destDir ) );
}

        .pipe( sourceMaps.init() )
        .pipe( vinylSource( fileName ) )
        .pipe( vinylBuffer() )
        .pipe( sourceMaps.init({ loadMaps: true }) )
        .pipe( sourceMaps.write( './' ) )
   */

/** 3.2.4. Comprehensive gulp tasks **/
/** Distribute all styles & scripts at each scope concurrently **/

gulp.task( 'distribute-all-frontend-resources', 
    gulp.series(
        'distribute-all-frontend-styles', 
        'distribute-all-frontend-scripts', 
        function(done){ done();}
    )    
);

gulp.task( 'distribute-all-admin-resources', 
    gulp.series(
        'distribute-all-admin-styles', 
        'distribute-all-admin-scripts', 
        function(done){ done();}
    )    
);


gulp.task( 'distribute-all-editor-resources', 
    gulp.series(
        'distribute-all-editor-styles', 
        'distribute-all-editor-scripts', 
        function(done){ done();}
    )    
);


// Export the default gulp task (assigning proper callback function)
module.exports.default = distributeAllAdminStyles;




