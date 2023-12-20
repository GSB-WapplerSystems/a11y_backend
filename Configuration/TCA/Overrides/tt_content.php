<?php

foreach (($GLOBALS['TCA']['tt_content']['containerConfiguration'] ?? []) as $key => $configuration) {
    $GLOBALS['TCA']['tt_content']['containerConfiguration'][$key]['gridPartialPaths'][] = 'EXT:a11y_backend/Resources/Private/Extensions/Container/Partials/';
}
