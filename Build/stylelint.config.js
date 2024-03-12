/* eslint-disable no-undef */
/*
    https://stylelint.io/user-guide/rules/list
*/
module.exports = {
  // eslint-disable-line no-undef
  extends: [
    'stylelint-config-recommended', // contains: https://github.com/stylelint/stylelint-config-recommended/blob/master/index.js
    'stylelint-config-recommended-scss', // contains: https://github.com/kristerkari/stylelint-config-recommended-scss/blob/master/index.js
    'stylelint-config-standard' // contains: https://github.com/stylelint/stylelint-config-standard/blob/master/index.js
  ],
  plugins: [
    'stylelint-scss', // https://github.com/kristerkari/stylelint-scss
    'stylelint-order' // https://github.com/hudochenkov/stylelint-order
  ],
  ignoreFiles: ['**/*.html', '**/*.js', '**/*.php'],
  rules: {
    'at-rule-empty-line-before': [
      'always',
      {
        except: ['first-nested', 'blockless-after-blockless'],
        ignore: 'after-comment'
      }
    ],
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true,
    'declaration-block-no-shorthand-property-overrides': true,
    'declaration-empty-line-before': null,
    'font-family-no-missing-generic-family-keyword': true,
    'no-descending-specificity': null,
    'order/properties-alphabetical-order': null,
    'selector-type-no-unknown': [true, {severity: 'warning'}],
    'selector-class-pattern': null,
    'scss/no-global-function-names': null,
    'declaration-block-no-redundant-longhand-properties': null,
    'function-url-quotes': null,
    'function-no-unknown': null,
    'property-no-vendor-prefix': [
      true,
      {
        ignoreProperties: ['backface-visibility', 'appearance']
      }
    ],
    'selector-id-pattern': null,
    'import-notation': null
  }
}
