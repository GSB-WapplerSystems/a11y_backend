<?php

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $newColumns = [
        'visible' => [
            //todo: deprecated refactor for TYPO3 13
            'exclude' => true,
            'label' => 'LLL:EXT:a11y_backend/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.visible.label',
            'description' => 'LLL:EXT:a11y_backend/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.visible.description',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'placeholder' => '__row|uid_local|metadata|visible',
                'mode' => 'useOrOverridePlaceholder',
                'default' => null,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_metadata', $newColumns);
})();
