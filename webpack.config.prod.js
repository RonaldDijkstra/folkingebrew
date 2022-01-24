const path = require('path');
const glob = require('glob');
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin')

const env = process.env.NODE_ENV;
const filename = env === 'production' ? '[name].[contenthash]' : '[name]';

module.exports = {
  bail: true,
  entry: {
    main: path.resolve(__dirname, './source/assets/javascripts/index.js'),
    snipcart: path.resolve(__dirname, './source/assets/javascripts/snipcart.js')
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

    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({
      filename: `${filename}.css`
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
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
