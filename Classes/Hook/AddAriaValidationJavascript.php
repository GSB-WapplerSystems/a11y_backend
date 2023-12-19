<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/a11y-backend of the GSB 11 Project by ITZBund
 *
 * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\A11yBackend\Hook;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;

class AddAriaValidationJavascript
{
    public function addJavascript(array $param, PageRenderer $pageRenderer)
    {
        if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
          && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()
        ) {
            $pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
                JavaScriptModuleInstruction::create('@itzbund/a11y-backend/aria-validation-hints.js')
            );
        }
    }
}
