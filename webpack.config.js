const webpack = require('webpack');
const path = require('path');
const package = require('./package.json');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const config = require( './config.json' );

const devMode = process.env.NODE_ENV !== 'production';

// Naming and path settings
var appName = 'app';
var entryPoint = {
  frontend: './src/frontend/main.js',
  admin: './src/admin/main.js',
  style: './assets/less/style.less',
};

var exportPath = path.resolve(__dirname, './assets/js');

// Enviroment flag
var plugins = [];
var env = process.env.NODE_ENV;

function isProduction() {
  return process.env.NODE_ENV === 'production';
}

// extract css into its own file
plugins.push(new MiniCssExtractPlugin({
  filename: '../css/[name].css',
  ignoreOrder: false, // Enable to remove warnings about conflicting order
}));

// plugins.push(new BrowserSyncPlugin( {
//   proxy: {
//     target: config.proxyURL
//   },
//   files: [
//     '**/*.php'
//   ],
//   cors: true,
//   reloadDelay: 0
// } ));

plugins.push(new VueLoaderPlugin());

// Differ settings based on production flag
if ( devMode ) {
  appName = '[name].js';
} else {
  appName = '[name].min.js';
}

module.exports = {
  entry: entryPoint,
  mode: devMode ? 'development' : 'production',
  output: {
    path: exportPath,
    filename: appName,
  },

  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': path.resolve('./src/'),
      'frontend': path.resolve('./src/frontend/'),
      'admin': path.resolve('./src/admin/'),
    },
    modules: [
      path.resolve('./node_modules'),
      path.resolve(path.join(__dirname, 'src/')),
    ]
  },

  optimization: {
    runtimeChunk: 'single',
    splitChunks: {
      cacheGroups: {
        vendor: {
          test: /[\\\/]node_modules[\\\/]/,
          name: 'vendors',
          chunks: 'all'
        }
      }
    },
    minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
  },

  plugins,

  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.js$/,
        use: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.less$/,
        use: [
          'vue-style-loader',
          'css-loader',
          'less-loader'
        ]
      },
      {
        test: /\.png$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              mimetype: 'image/png'
            }
          }
        ]
      },
      {
        test: /\.svg$/,
        use: 'file-loader'
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: (resourcePath, context) => {
                return path.relative(path.dirname(resourcePath), context) + '/';
              },
              hmr: process.env.NODE_ENV === 'development',
            },
          },
          'css-loader',
        ],
      },
    ]
  },
}
