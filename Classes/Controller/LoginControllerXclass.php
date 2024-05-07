<?php

/*
 * This file is part of the package itzbund/a11y-backend of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Christian Rath-Ulrich, Patrick Schriner
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

declare(strict_types=1);

namespace ITZBund\A11yBackend\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller responsible for rendering the TYPO3 Backend login form.
 *
 * @internal This class is a specific Backend controller implementation and is not considered part of the Public TYPO3 API.
 */
#[Controller]
class LoginControllerXclass extends \TYPO3\CMS\Backend\Controller\LoginController
{
    /**
     * Initialize the login box. Will also react on a &L=OUT flag and exit.
     */
    protected function init(ServerRequestInterface $request): void
    {
        parent::init($request);
        $this->view->setTemplateRootPaths(
            array_merge(
                $this->view->getTemplateRootPaths(),
                ['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Templates']
            )
        );
        $this->view->setLayoutRootPaths(
            array_merge(
                $this->view->getLayoutRootPaths(),
                ['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Layouts']
            )
        );
        $this->view->setPartialRootPaths(
            array_merge(
                $this->view->getPartialRootPaths(),
                ['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Partials']
            )
        );
    }
}
