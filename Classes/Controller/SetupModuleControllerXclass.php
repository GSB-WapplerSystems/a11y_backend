<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ITZBund\A11yBackend\Controller;

use JetBrains\PhpStorm\NoReturn;
use TYPO3\CMS\Backend\Backend\Avatar\DefaultAvatarProvider;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Script class for the Setup module
 *
 * @internal This is a specific Backend Controller implementation and is not considered part of the Public TYPO3 API.
 */
#[Controller]
class SetupModuleControllerXclass extends \TYPO3\CMS\Setup\Controller\SetupModuleController
{

    /**
     * renders the data for all tabs in the user setup and returns
     * everything that is needed with tabs and dyntab menu
     *
     * @return array Ready to use for the dyntabmenu itemarray
     * @throws RouteNotFoundException
     */
    #[NoReturn] protected function renderUserSetup(): array
    {
        $backendUser = $this->getBackendUser();
        $html = '';
        $result = [];
        $firstTabLabel = '';
        $code = [];
        $fieldArray = $this->getFieldsFromShowItem();
        $tabLabel = '';
        foreach ($fieldArray as $fieldName) {
            if (str_starts_with($fieldName, '--div--;')) {
                if ($firstTabLabel === '') {
                    // First tab
                    $tabLabel = $this->getLabel(substr($fieldName, 8), '', false);
                    $firstTabLabel = $tabLabel;
                } else {
                    $result[] = [
                        'label' => $tabLabel,
                        'content' => count($code) ? implode(LF, $code) : '',
                    ];
                    $tabLabel = $this->getLabel(substr($fieldName, 8), '', false);
                    $code = [];
                }
                continue;
            }

            $config = $GLOBALS['TYPO3_USER_SETTINGS']['columns'][$fieldName] ?? null;
            if ($config && isset($config['access']) && !$this->checkAccess($config)) {
                continue;
            }

            $label = $this->getLabel($config['label'] ?? '', $fieldName);

            $type = $config['type'] ?? '';
            $class = $config['class'] ?? '';
            if ($type !== 'check' && $type !== 'select') {
                $class .= ' form-control';
            }
            if ($type === 'select') {
                $class .= ' form-select';
            }
            $more = '';
            if ($class) {
                $more .= ' class="' . htmlspecialchars($class) . '"';
            }
            $style = $config['style'] ?? '';
            if ($style) {
                $more .= ' style="' . htmlspecialchars($style) . '"';
            }
            if (isset($this->overrideConf[$fieldName])) {
                $more .= ' disabled="disabled"';
            }
            $isBeUsersTable = ($config['table'] ?? false) === 'be_users';
            $value = $isBeUsersTable ? ($backendUser->user[$fieldName] ?? false) : ($backendUser->uc[$fieldName] ?? false);
            if (!$value && isset($config['default'])) {
                $value = $config['default'];
            }
            $dataAdd = $isBeUsersTable ? '[be_users]' : '';

            switch ($type) {
                case 'text':
                case 'number':
                case 'email':
                case 'password':
                    $noAutocomplete = '';

                    $maxLength = $config['max'] ?? 0;
                    if ((int)$maxLength > 0) {
                        $more .= ' maxlength="' . (int)$maxLength . '"';
                    }

                    if ($type === 'password') {
                        $value = '';
                        $noAutocomplete = 'autocomplete="new-password" ';
                    }
                    $html = '<input id="field_' . htmlspecialchars($fieldName) . '"
                        type="' . htmlspecialchars($type) . '"
                        aria-describedby="description_' . htmlspecialchars($fieldName) . '"
                        name="data' . $dataAdd . '[' . htmlspecialchars($fieldName) . ']" ' .
                        $noAutocomplete .
                        'value="' . htmlspecialchars((string)$value) . '" ' .
                        $more .
                        ' />';

                    if ($fieldName === 'password' && $this->passwordPolicyValidator->isEnabled() && $this->passwordPolicyValidator->hasRequirements()) {
                        $description = $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_password_policy.xlf:passwordRequirements.description');
                        $html .= '<div id="description_' . htmlspecialchars($fieldName) . '"><p class="mt-2 mb-1 text-body-secondary">' . htmlspecialchars($description) . '</p>';
                        $html .= '<ul class="mb-0"><li class="text-body-secondary">' . implode('</li><li class="text-body-secondary">', $this->passwordPolicyValidator->getRequirements()) . '</li></ul></div>';
                    }

                    break;
                case 'check':
                    $html = '<input id="field_' . htmlspecialchars($fieldName) . '"
                        type="checkbox"
                        class="form-check-input"
                        name="data' . $dataAdd . '[' . htmlspecialchars($fieldName) . ']"' .
                        ($value ? ' checked="checked"' : '') .
                        $more .
                        ' />';
                    break;
                case 'language':
                    $html = $this->renderLanguageSelect();
                    break;
                case 'select':
                    if ($config['itemsProcFunc'] ?? false) {
                        $html = GeneralUtility::callUserFunction($config['itemsProcFunc'], $config, $this);
                    } else {
                        $html = '<select id="field_' . htmlspecialchars($fieldName) . '"
                            name="data' . $dataAdd . '[' . htmlspecialchars($fieldName) . ']"' .
                            $more . '>' . LF;
                        foreach ($config['items'] as $key => $optionLabel) {
                            $html .= '<option value="' . htmlspecialchars($key) . '"' . ($value == $key ? ' selected="selected"' : '') . '>' . $this->getLabel($optionLabel, '', false) . '</option>' . LF;
                        }
                        $html .= '</select>';
                    }
                    break;
                case 'user':
                    $html = GeneralUtility::callUserFunction($config['userFunc'], $config, $this);
                    break;
                case 'button':
                    $label = $this->getLabel($config['label'] ?? '');
                    if (!empty($config['clickData'])) {
                        $clickData = $config['clickData'];
                        $buttonAttributes = [
                            'type' => 'button',
                            'class' => 'btn btn-default',
                            'value' => $this->getLabel($config['buttonlabel'], '', false),
                        ];
                        if (isset($clickData['eventName'])) {
                            $buttonAttributes['data-event'] = 'click';
                            $buttonAttributes['data-event-name'] = htmlspecialchars($clickData['eventName']);
                            $buttonAttributes['data-event-payload'] = htmlspecialchars($fieldName);
                        }
                        $html = '<input '
                            . GeneralUtility::implodeAttributes($buttonAttributes, false) . ' />';
                    }
                    if (!empty($config['confirm'])) {
                        $confirmData = $config['confirmData'];
                        // cave: values must be processed by `htmlspecialchars()`
                        $buttonAttributes = [
                            'type' => 'button',
                            'class' => 'btn btn-default t3js-modal-trigger',
                            'data-severity' => 'warning',
                            'data-title' => $this->getLabel($config['label'], '', false),
                            'data-bs-content' => $this->getLabel($confirmData['message'], '', false),
                            'value' => htmlspecialchars($this->getLabel($config['buttonlabel'], '', false)),
                        ];
                        if (isset($confirmData['eventName'])) {
                            $buttonAttributes['data-event'] = 'confirm';
                            $buttonAttributes['data-event-name'] = htmlspecialchars($confirmData['eventName']);
                            $buttonAttributes['data-event-payload'] = htmlspecialchars($fieldName);
                        }
                        $html = '<input '
                            . GeneralUtility::implodeAttributes($buttonAttributes, false) . ' />';
                    }
                    break;
                case 'avatar':
                    // Get current avatar image
                    $html = '';
                    $avatarFileUid = $this->getAvatarFileUid((int)$backendUser->user['uid']);

                    if ($avatarFileUid) {
                        $defaultAvatarProvider = GeneralUtility::makeInstance(DefaultAvatarProvider::class);
                        $avatarImage = $defaultAvatarProvider->getImage($backendUser->user, 32);
                        if ($avatarImage) {
                            $icon = '<span class="avatar avatar-size-medium mb-2"><span class="avatar-image">' .
                                '<img alt="" src="' . htmlspecialchars($avatarImage->getUrl()) . '"' .
                                ' width="' . (int)$avatarImage->getWidth() . '"' .
                                ' height="' . (int)$avatarImage->getHeight() . '" />' .
                                '</span></span>';
                            $html .= '<span id="image_' . htmlspecialchars($fieldName) . '">' . $icon . ' </span>';
                        }
                    }
                    $html .= '<input id="field_' . htmlspecialchars($fieldName) . '" type="hidden" ' .
                            'name="data' . $dataAdd . '[' . htmlspecialchars($fieldName) . ']"' . $more .
                            ' value="' . $avatarFileUid . '" data-setup-avatar-field="' . htmlspecialchars($fieldName) . '" />';

                    $html .= '<typo3-formengine-container-files><div class="form-group"><div class="form-group"><div class="form-control-wrap">';
                    $html .= '<button type="button" id="add_button_' . htmlspecialchars($fieldName)
                        . '" class="btn btn-default"'
                        . ' title="' . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:avatar.openFileBrowser')) . '"'
                        . ' data-setup-avatar-url="' . htmlspecialchars((string)$this->uriBuilder->buildUriFromRoute('wizard_element_browser', ['mode' => 'file', 'bparams' => '|||allowed=' . ($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] ?? '') . ';disallowed=|-0-be_users-avatar-avatar'])) . '"'
                        . '>' . $this->iconFactory->getIcon('actions-insert-record', Icon::SIZE_SMALL)
                        . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:avatar.openFileBrowser'))
                        . '</button>';
                    if ($avatarFileUid) {
                        // Keep space between both buttons with a whitespace (like for other buttons)
                        $html .= ' ';
                        $html .= '<button type="button" id="clear_button_' . htmlspecialchars($fieldName)
                        . '" class="btn btn-default"'
                        . ' title="' . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:avatar.clear')) . '" '
                        . '>' . $this->iconFactory->getIcon('actions-delete', Icon::SIZE_SMALL)
                        . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:avatar.clear'))
                        . '</button>';
                    }
                    $html .= '</div></div></div></typo3-formengine-container-files>';
                    break;
                case 'mfa':
                    $label = $this->getLabel($config['label'] ?? '');
                    $html = '';
                    $lang = $this->getLanguageService();
                    $hasActiveProviders = $this->mfaProviderRegistry->hasActiveProviders($backendUser);
                    if ($hasActiveProviders) {
                        if ($this->mfaProviderRegistry->hasLockedProviders($backendUser)) {
                            $html .= ' <span class="badge badge-danger">' . htmlspecialchars($lang->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:mfaProviders.lockedMfaProviders')) . '</span>';
                        } else {
                            $html .= ' <span class="badge badge-success">' . htmlspecialchars($lang->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:mfaProviders.enabled')) . '</span>';
                        }
                    }
                    $html .= '<div class="formengine-field-item t3js-formengine-field-item">';
                    $html .= '<div class="form-description">' . nl2br(htmlspecialchars($lang->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:mfaProviders.description'))) . '</div>';
                    if (!$this->mfaProviderRegistry->hasProviders()) {
                        $html .= '<span class="badge badge-danger">' . htmlspecialchars($lang->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:mfaProviders.notAvailable')) . '</span>';
                        break;
                    }
                    $html .= '<div class="form-group"><div class="form-group"><div class="form-control-wrap t3js-file-controls">';
                    $html .= '<a href="' . htmlspecialchars((string)$this->uriBuilder->buildUriFromRoute('mfa')) . '" class="btn btn-default">';
                    $html .=  htmlspecialchars($lang->sL('LLL:EXT:setup/Resources/Private/Language/locallang.xlf:mfaProviders.' . ($hasActiveProviders ? 'manageLinkTitle' : 'setupLinkTitle')));
                    $html .= '</a>';
                    $html .= '</div></div></div></div>';
                    break;
                default:
                    $html = '';
            }

            $htmlPrepended = '';
            $htmlAppended = '';
            if ($type === 'button') {
                $htmlPrepended = '<div class="formengine-field-item t3js-formengine-field-item"><div class="form-group">'
                    . '<div class="form-group"><div class="form-control-wrap t3js-file-controls">';
                $htmlAppended = '</div></div></div></div>';
            }
            if ($type === 'check') {
                $htmlPrepended = '<div class="formengine-field-item t3js-formengine-field-item"><div class="form-wizards-wrap">'
                    . '<div class="form-wizards-element"><div class="form-check form-switch">';
                $htmlAppended = '</div></div></div></div>';
            }
            if ($type === 'select' || $type === 'language') {
                $htmlPrepended = '<div class="formengine-field-item t3js-formengine-field-item"><div class="form-control-wrap">'
                    . '<div class="form-wizards-wrap"><div class="form-wizards-element"><div class="input-group">';
                $htmlAppended = '</div></div></div></div></div>';
            }
            if ($type === 'text' || $type === 'number' || $type === 'email' || $type === 'password') {
                $htmlPrepended = '<div class="formengine-field-item t3js-formengine-field-item"><div class="form-control-wrap">'
                    . '<div class="form-wizards-wrap"><div class="form-wizards-element">';
                $htmlAppended = '</div></div></div></div>';
            }

            $code[] = '<fieldset class="form-section"><div class="row"><div class="form-group col-md-12">'
                . $label
                . $htmlPrepended
                . $html
                . $htmlAppended
                . '</div></div></fieldset>';
        }

        $result[] = [
            'label' => $tabLabel,
            'content' => count($code) ? implode(LF, $code) : '',
        ];
        return $result;
    }


}
