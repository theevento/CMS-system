var Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

Encore
// the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    // uncomment to create hashed filenames (e.g. app.abc123.scss)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('app', './assets/js/app.js')
    .addEntry('main', './assets/scss/main.scss')
    .enableVueLoader()
    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

// uncomment for legacy applications that require $/jQuery as a global variable
// .autoProvidejQuery()
;

const webpackConfig = Encore.getWebpackConfig();
webpackConfig.plugins = webpackConfig.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
);
webpackConfig.plugins.push(new UglifyJsPlugin());

module.exports = webpackConfig;