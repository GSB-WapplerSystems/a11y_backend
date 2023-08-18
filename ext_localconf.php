<?php

/*
 * This file is part of the package itzbund/a11y-backend.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1529672977] = [
    'provider' => OAuth2LoginProvider::class,
    'sorting' => 25,
    'icon-class' => 'fa-sign-in',
    'label' => 'LLL:EXT:oauth2/Resources/Private/Language/locallang.xlf:oauth2.login.link'
];

(function () {
    // https://docs.typo3.org/c/typo3/cms-core/main/en-us//Changelog/12.3/Deprecation-100033-TBE_STYLESStylesheetAndStylesheet2.html
    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['a11y_backend'] = 'EXT:a11y_backend/Resources/Public/StyleSheets/screen.css';


})();
