const path = require('path');
module.exports = {
  entry: './sass/style.scss',
  output: {
    filename: 'style.css',
    path: path.resolve(__dirname, 'css'),
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          'style-loader',
          'css-loader',
          'sass-loader'
        ]
      }
    ]
  }
};

