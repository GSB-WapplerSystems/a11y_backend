<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
 * This file is part of the package itzbund/a11y-backend of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Michael Max Busch, Ole Hartwig, Christian Rath-Ulrich
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use ITZBund\A11yBackend\Hook\AddAriaValidationJavascript;

defined('TYPO3') or die('Access denied.');

//todo: replace when other method is available
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Backend\Controller\LoginController::class] = [
    'className' => \ITZBund\A11yBackend\Controller\LoginControllerXclass::class,
];

//todo: replace when other method is available
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Setup\Controller\SetupModuleController::class] = [
    'className' => \ITZBund\A11yBackend\Controller\SetupModuleControllerXclass::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['a11y_backend_aria_validation'] = AddAriaValidationJavascript::class . '->addJavascript';

(function () {
    // https://docs.typo3.org/c/typo3/cms-core/main/en-us//Changelog/12.3/Deprecation-100033-TBE_STYLESStylesheetAndStylesheet2.html
    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['a11y_backend'] = 'EXT:a11y_backend/Resources/Public/StyleSheets/screen.css';
})();
