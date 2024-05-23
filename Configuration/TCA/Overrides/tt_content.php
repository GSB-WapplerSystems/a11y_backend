<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

foreach (($GLOBALS['TCA']['tt_content']['containerConfiguration'] ?? []) as $key => $configuration) {
    $GLOBALS['TCA']['tt_content']['containerConfiguration'][$key]['gridPartialPaths'][] = 'EXT:a11y_backend/Resources/Private/Extensions/Container/Partials/';
}
