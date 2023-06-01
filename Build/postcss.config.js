module.exports = {
  plugins: {
    // inline-svg
    'postcss-inline-svg': {},

    // preset-env
    'postcss-preset-env': {
      browsers: 'defaults'
    },

    // pxtorem
    'postcss-pxtorem': {
      rootValue: 16,
      propList: ['*']
    }
  }
}
