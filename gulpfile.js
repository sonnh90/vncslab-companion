/**
 * Package: vncslab-companion
 * Author: Leon Nguyen (sonnh2109@gmail.com)
*/


/**============================================================= **/
/**=== 1. Declare module variables ===  **/
/**============================================================= **/
/** Search for gulp package in nodejs directory **/


import gulp from 'gulp';
import gulpBabel from 'gulp-babel';
import gulpDebug from 'gulp-debug';
import * as sass from 'sass';

import gulpSassInstance from 'gulp-sass';
var gulpSass = gulpSassInstance( sass );

import gulpRenamer from 'gulp-rename';
import autoPrefixer from 'gulp-autoprefixer';
import sourceMaps from 'gulp-sourcemaps';
import browserify from 'browserify';
import babelify from 'babelify';
import vinylSource from 'vinyl-source-stream';
import vinylBuffer from 'vinyl-buffer';
import gulpUglify from 'gulp-uglify';
import gulpPlumber from 'gulp-plumber';
import vinylSourceStream from 'vinyl-source-stream';
import * as glob from 'glob';
// var glob = require('glob');

import browserSyncInstance from 'browser-sync';
var browserSync = browserSyncInstance.create();
var browserReloader = browserSync.reload();



/**============================================================= **/
/**=== 2. Variables for relevant Gulp task === **/
/**============================================================= **/

/** 
 * 2.1. Variable naming explanation
 * - srcDir : Source Directory.
 * + This is a relative path starting from the current root plugin directory
 * - distDir : Distribution directory - where SCSS & ES6 JS are compiled to CSS & ES5 JS
 * **/

const resourcesInfo = {
    styles:{
        prelib:{
            srcBootstrapFile:'node_modules/bootstrap/scss/bootstrap.scss',
            srcDir:'./sources/scope-prelib/scss/',
            srcListFile:'./sources/scope-prelib/scss/*.scss',
            distDir:'./assets/scope-prelib/css/'
        },
        admin:{
            scrDir:'./sources/scope-admin/scss/',
            scrListFile:'./sources/scope-admin/scss/*.scss',
            distDir:'./assets/scope-admin/css/',
        },
        editor:{
            srcDir:'./sources/scope-editor/scss/',
            scrListFile:'./sources/scope-editor/scss/*.scss',
            distDir:'./assets/scope-editor/css/',
        },
        frontend:{
            srcDir:'./sources/css/scope-frontend/',
            srcListFile:'./sources/scope-frontend/scss/*.scss',
            distDir:'./assets/scope-frontend/css/',
        }
    },
    scripts:{
        prelib:{
            srcDir:'./sources/scope-prelib/js/',
            srcListFile:'./sources/scope-prelib/js/*.js',
            distDir:'./assets/scope-prelib/js/'
        },
        admin:{
            srcDir:'./sources/scope-admin/js/',
            srcListFile:'./sources/scope-admin/js/*.js',
            distDir:'./assets/scope-admin/js/'
        },
        editor:{
            srcDir:'./sources/scope-editor/js/',
            srcListFile:'./sources/scope-editor/js/*.js',
            distDir:'./assets/scope-editor/js/'
        },
        frontend:{
            srcDir:'./sources/scope-frontend/js/',
            srcListFile:'./sources/scope-frontend/js/*.js',
            distDir:'./assets/scope-frontend/js/'
        }
    }
}

/**============================================================= **/
/**=== 3. Gulp tasks definition - defining elemental tasks === **/
/** All elemental tasks must be defined before declaring composite gulp tasks */
/**============================================================= **/


/**=== 3.1. Distribute all styles files - SCSS to CSS === **/

gulp.task( 'distribute-all-frontend-styles', distribute_all_frontend_styles );

/**=== 3.1.1. Distribute all styles files for frontend display === **/

function distribute_all_frontend_styles( done ){
    /** 1. Distribute all frontend display SCSS to CSS in beauty format */
    distribute_all_scss_to_css_beauty_format(
        resourcesInfo.styles.frontend.srcListFile,
        resourcesInfo.styles.frontend.distDir
    );

    done();
};//distribute_all_frontend_styles

/**=== 3.1-extra Helper functions Distribute all styles files - SCSS to CSS === **/
/** 1. Distribute all SCSS to CSS */
function distribute_all_scss_to_css_beauty_format( scssSourceDir, cssDistDir){
    /**
     * Temporary remove the ".pipe( sourceMaps.write('./') )" before gulp.dest
    */
    return gulp.src( scssSourceDir )
        .pipe( gulpPlumber() )
        .pipe( sourceMaps.init() )
        .pipe(
            gulpSass(
                {
                    includePath:['node_modules/bootstrap/scss'],
                    errorLogToConsole: true
                }
            )
            .on( 'error', console.error.bind( console ) )
        )
        .pipe(
            autoPrefixer( { cascade: false } )
        )
        .pipe( gulp.dest( cssDistDir ) )
        .pipe( browserSync.stream() );

}//distribute_all_scss_to_css_beauty_format

/** 2. Distribute single file SCSS to CSS */
function distribute_single_scss_to_css_beauty_format( scssSrcFilePath, cssDistDir ){
    /**
     * Temporary remove the ".pipe( sourceMaps.write('./') )" before gulp.dest
    */
    return gulp.src( scssSrcFilePath )
        .pipe( gulpPlumber() )
        .pipe( sourceMaps.init() )
        .pipe(
            gulpSass(
                {
                    includePath:['node_modules/bootstrap/scss'],
                    errorLogToConsole: true
                }
            )
            .on( 'error', console.error.bind( console ) )
        )
        .pipe( 
            gulpRenamer( 
                function(filePath){ 
                    filePath.basename += "-import"; 
                    filePath.extname = ".css";
                } 
            ) 
        )
        .pipe( gulp.dest( cssDistDir ) )
        .pipe( browserSync.stream() );

}//distribute_single_scss_to_css_beauty_format

/** 3. Distribute all SCSS to minified CSS */
function distribute_all_scss_to_css_minified_format( scssSourceDir, cssDistDir ){
    /**
     * Temporary remove the ".pipe( sourceMaps.write('./') )" before gulp.dest
    */
    return gulp.src( scssSourceDir )
        .pipe( gulpPlumber() )
        .pipe( sourceMaps.init() )
        .pipe(
            gulpSass(
                {
                    includePath:['node_modules/bootstrap/scss'],
                    errorLogToConsole: true,
                    outputStyle:'compressed'
                }
            )
            .on( 'error', console.error.bind( console ) )
        )
        .pipe(
            autoPrefixer( { cascasde: false } )
        )
        .pipe( gulpRenamer( { suffix: '.min' } ) )
        .pipe( gulp.dest( cssDistDir ) )
        .pipe( browserSync.stream() );
}//distribute_all_scss_to_css_minified_format


/**=== 3.2. Distribute all script files - JS ES6 to Vanilla JS === **/
gulp.task( 'distribute-all-frontend-scripts', distribute_all_frontend_scripts );

/** 3.2.1. Distribute all frontend JS ES 6 to vanilla JS */
/** Need to specify each JS ES6 files manually */
function distribute_all_frontend_scripts( done ){

    distribute_all_modern_js_to_vanilla_js(
        resourcesInfo.scripts.frontend.srcDir,
        resourcesInfo.scripts.frontend.srcListFile,
        resourcesInfo.scripts.frontend.distDir
    );
    
    done();
};

/**=== 3.2-extra Helper functions Distribute all scripts files - JS ES6 to Vanilla JS === **/
/** 1. Distribute a single modern JS file to vanilla JS file */
function distribute_single_esnext_js_to_vanilla_js( jsSrcFileDir, jsSrcFileName, jsDistDir ){
    /**
     * Temporary remove the ".pipe( sourceMaps.write('./') )" before gulp.dest
    */

    let jsFileAbsPath = `${jsSrcFileDir}${jsSrcFileName}`;

    // return browserify( { entries: [ jsSrcFileDir + jsSrcFileName ] } )
    return browserify( { entries: [ jsFileAbsPath ] } )
        .transform( babelify, { presets: ["@babel/preset-env"] } )
        .bundle()
        .pipe( vinylSource( jsSrcFileName ) )
        .pipe( gulpPlumber() )
        .pipe( gulpRenamer( { extname: ".js"} ) )
        .pipe( vinylBuffer() )
        .pipe( sourceMaps.init( { loadMaps: true } ) )
        .pipe( gulp.dest( jsDistDir ) )
        .pipe( browserSync.stream() );
}//distribute_single_modern_js_to_vanilla_js

/** 2. Distribute all modern JS files to vanilla JS */
function distribute_all_modern_js_to_vanilla_js( jsSrcFileDir, srcListFile, jsDistDir ){
    // the srcListFile must have glob format, such as: ""./sources/scope-frontend/js/*.js"
    
    /** 1. Obtain a list of javascript file in array 
     * Sample format: sources\scope-frontend\js\wc-custom-product-page.js
    */
    const jsSrcFilesArray = glob.sync( srcListFile );//OK

    for( let jsSrcFilePath of jsSrcFilesArray ){
        // Get the source file name from jsSrcFileRelativePath
        let jsSrcFileName = jsSrcFilePath.replace( /^.*[\\/]/, '' );//OK
     
        distribute_single_esnext_js_to_vanilla_js( jsSrcFileDir, jsSrcFileName, jsDistDir );
    }//End of for loop "let jsSrcFilePath of jsSrcFilesArray"

}//distribute_all_modern_js_to_vanilla_js


/**=== 4, Global tasks === */

/** 3.0.1. Distribute all frontend resources **/
gulp.task(
    'distribute-all-frontend-resources',
    gulp.series( 
        'distribute-all-frontend-styles', 
        'distribute-all-frontend-scripts' 
    )
);


/**============================================================= **/
/**=== 5. Troubleshotting === **/
/**============================================================= **/




