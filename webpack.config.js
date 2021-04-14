const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const path = require('path');

// Directories
const FONT_DIR = 'assets/fonts/'; // (woff(2)?|ttf|otf|eot|svg)
const IMG_DIR = 'assets/img/'; // (jpeg|bmp|jpg|ico|png|svg)
const SVG_DIR = 'assets/svg/';
const SCRIPT_DIR = 'js/';
const STYLE_DIR = 'css/';
const SCSS_DIR = 'scss/';

// Filenames
// Prepend directory names to create subdirectories
// For example 'output/main.[hash].js'
const SCSS_ENTRY = 'style.scss';
const JS_ENTRY = '_index.js';
const CSS_OUTPUT = 'style.min.css';
const JS_OUTPUT = '_app.js';

// https://v4.webpack.js.org
module.exports = (env, argv) => {
    // Environment flag to determine source mapping
    const devMode = argv.mode !== 'production';
    // Configure plugin to extract and output CSS
    const extractCssPlugin = new MiniCssExtractPlugin({
        filename: '../' + STYLE_DIR + CSS_OUTPUT
    });

    // Create array for plugin configs
    let pluginArray = [extractCssPlugin];

    // Return webpack configuration object
    return {
        bail: true,
        devtool: devMode ? 'source-map' : '',
        entry: [
            './' + SCRIPT_DIR + JS_ENTRY,
            './' + SCSS_DIR + SCSS_ENTRY
        ],
        optimization: {
            minimizer: [
                new TerserPlugin(),
                new OptimizeCSSAssetsPlugin({})
            ]
        },
        output: {
            filename: JS_OUTPUT,
            path: path.resolve(__dirname, SCRIPT_DIR),
            publicPath: '/'
        },
        performance: {
            hints: false
        },
        plugins: pluginArray,
        module: {
            rules: [{
                test: /\.js$/,
                enforce: 'pre',
                exclude: path.resolve(__dirname, 'node_modules/'),
                use: [{
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/env'],
                        minified: true,
                        comments: false,
                        compact: true
                    }
                }]
            }, {
                test: /\.(s*)css$/,
                include: path.resolve(__dirname, SCSS_DIR),
                exclude: path.resolve(__dirname, 'node_modules/'),
                use: [{
                    loader: MiniCssExtractPlugin.loader,
                    options: {
                        sourceMap: devMode
                    }
                }, {
                    loader: 'css-loader',
                    options: {
                        sourceMap: devMode
                    }
                }, {
                    loader: 'postcss-loader',
                    options: {
                        sourceMap: devMode,
                        ident: 'postcss',
                        plugins: () => [
                            require('autoprefixer')
                        ]
                    }
                }, {
                    loader: 'sass-loader',
                    options: {
                        sourceMap: devMode
                    }
                }]
            }, {
                test: /\.(woff(2)?|ttf|otf|eot|svg)$/i,
                include: path.resolve(__dirname, FONT_DIR),
                exclude: path.resolve(__dirname, 'node_modules/'),
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: '../' + FONT_DIR,
                        publicPath: '../' + FONT_DIR
                    }
                }]
            }, {
                test: /\.svg$/i,
                include: path.resolve(__dirname, SVG_DIR),
                exclude: path.resolve(__dirname, 'node_modules/'),
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: '../' + SVG_DIR,
                        publicPath: '../' + SVG_DIR
                    }
                }]
            }, {
                test: /\.(jpeg|bmp|jpg|ico|png|svg)$/i,
                include: path.resolve(__dirname, IMG_DIR),
                exclude: path.resolve(__dirname, 'node_modules/'),
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: '../' + IMG_DIR,
                        publicPath: '../' + IMG_DIR
                    }
                }]
            }]
        }
    };
};
