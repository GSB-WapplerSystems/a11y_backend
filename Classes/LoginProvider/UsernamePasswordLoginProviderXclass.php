<?php



namespace ITZBund\A11yBackend\LoginProvider;

use TYPO3\CMS\Backend\Authentication\PasswordReset;
use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Backend\LoginProvider\UsernamePasswordLoginProvider;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * The default username + password based backend login form.
 *
 * @internal This class is a specific Backend implementation and is not considered part of the Public TYPO3 API.
 */
class UsernamePasswordLoginProviderXclass extends UsernamePasswordLoginProvider
{
    public function render(StandaloneView $view, PageRenderer $pageRenderer, LoginController $loginController): void
    {
       //todo: replace wen other method is avalible
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Templates/Login/UserPassLoginForm.html'));
        $request = $loginController->getCurrentRequest();
        if ($request->getAttribute('normalizedParams')->isHttps()) {
            $username = $request->getParsedBody()['u'] ?? $request->getQueryParams()['u'] ?? null;
            $password = $request->getParsedBody()['p'] ?? $request->getQueryParams()['p'] ?? null;
            $view->assignMultiple([
                'presetUsername' => $username,
                'presetPassword' => $password,
            ]);
        }
        $view->assign('enablePasswordReset', GeneralUtility::makeInstance(PasswordReset::class)->isEnabled());
    }
}
