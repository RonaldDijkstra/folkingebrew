const path = require('path');
const glob = require('glob');
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin')

const env = process.env.NODE_ENV;
const filename = env === 'production' ? '[name].[contenthash]' : '[name]';
const bail = env === 'production' ? true : false;

module.exports = {
  bail: bail,
  entry: {
    main: path.resolve(__dirname, './source/assets/javascripts/index.js'),
    snipcart: path.resolve(__dirname, './source/assets/javascripts/snipcart.js'),
    thanks: path.resolve(__dirname, './source/assets/javascripts/thanks.js'),
    webshop: path.resolve(__dirname, './source/assets/javascripts/webshop.js')
  },

  output: {
    path: `${__dirname}/dist`,
    filename: `${filename}.js`
  },

  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules)/,
        loader: 'babel-loader'
      },
      {
        test: /\.(sa|sc|c)ss$/,
        exclude: /node_modules/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader']
      },
      {

        test: /\.(woff|woff2|eot|ttf|otf)$/i,

        type: 'asset/resource',
        generator: {
          filename: './fonts/[name][ext]',
        },
      },
      {
        test: /\.(gif|png|jpe?g)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'assets/images/'
            }
          }
        ]
      }
    ]
  },

  plugins: [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({
      filename: `${filename}.css`
    })
  ],

  optimization: { 
    minimize: true, 
    minimizer: [ 
      new TerserPlugin({
        parallel: true, 
        terserOptions: { 
          output: {
            comments: false
          }
        },
        extractComments: false,
      }), 
    ]
  }
};
