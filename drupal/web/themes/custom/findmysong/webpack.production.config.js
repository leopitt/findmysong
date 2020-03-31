const path = require('path');
const SassLintPlugin = require('sass-lint-webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const public_asset_path = '/themes/custom/findmysong/dist/';
let pathsToClean = ['dist'];

module.exports = {
  entry: {
    index: './src/index.js'
  },
  externals: {
    "jquery": "jQuery"
  },
  plugins: [
    new SassLintPlugin({
      configPath: './config/.sass-lint.yml'
    }),
    new CleanWebpackPlugin(pathsToClean),
    new MiniCssExtractPlugin({
      filename: "[name].bundle.css"
    })
  ],
  output: {
    filename: '[name].bundle.js',
    path: path.resolve(__dirname, 'dist')
  },
  mode: 'production',
  module: {
    rules: [
      {
        test: /\.(scss)$/,
        use: [{
          loader: MiniCssExtractPlugin.loader
        }, {
          loader: 'css-loader',
        }, {
          loader: 'sass-loader'
        }]
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        use: [{
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            outputPath: 'images/',
            publicPath: public_asset_path + 'images/'
          }
        }]
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: [{
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            outputPath: 'fonts/',
            publicPath: public_asset_path + 'fonts/'
          }
        }]
      },
      {
        test: /\.twig$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              context: 'src',
              name: '[path][name].[ext]',
            },
          }
        ],
      },
    ]
  }
};
