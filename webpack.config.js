const path = require('path');
var webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {

  entry: {
    javascript: [
      path.resolve(__dirname, './source/assets/javascripts/index.js')
    ],
    all: [
      path.resolve(__dirname, './source/assets/stylesheets/all.css.scss')
    ],
    snipcart: [
      path.resolve(__dirname, './source/assets/stylesheets/snipcart.css.scss')
    ]
  },

  output: {
    path: `${__dirname}/dist`,
    filename: '[name].bundle.js'
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
        test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'assets/fonts/'
            }
          }
        ]
      },

      {
        test: /\.(gif|png|jpe?g|svg)$/,
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

    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),

    new MiniCssExtractPlugin({
      filename: '[name].bundle.css'
    }),
  
  ],
};
