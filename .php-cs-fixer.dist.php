<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()
    ->exclude('Build')
    ->exclude('.composer')
    ->in(__DIR__ );
return $config;
