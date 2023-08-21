<?php

declare(strict_types=1);

namespace ITZBund\A11yBackend\Controller;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\RouteRedirect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

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
        $languageService = $this->getLanguageService();
        $backendUser = $this->getBackendUserAuthentication();
        $parsedBody = $request->getParsedBody();
        $queryParams = $request->getQueryParams();

        // Try to get the preferred browser language
        $httpAcceptLanguage = $request->getServerParams()['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $preferredBrowserLanguage = $this->locales->getPreferredClientLanguage($httpAcceptLanguage);

        // If we found a $preferredBrowserLanguage, which is not the default language, while no user is logged in,
        // initialize $this->getLanguageService() and set the language to the backend user object, so labels in fluid
        // views are translated
        if (empty($backendUser->user['uid'])) {
            $languageService->init($this->locales->createLocale($preferredBrowserLanguage));
            $backendUser->user['lang'] = $preferredBrowserLanguage;
        }

        $this->setUpBasicPageRendererForBackend($this->pageRenderer, $this->extensionConfiguration, $request, $languageService);
        $this->pageRenderer->setTitle('TYPO3 CMS Login: ' . ($GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] ?? ''));

        $this->redirectUrl = GeneralUtility::sanitizeLocalUrl($parsedBody['redirect_url'] ?? $queryParams['redirect_url'] ?? '');
        $this->loginProviderIdentifier = $this->loginProviderResolver->resolveLoginProviderIdentifierFromRequest($request, 'be_lastLoginProvider');

        $this->loginRefresh = (bool)($parsedBody['loginRefresh'] ?? $queryParams['loginRefresh'] ?? false);
        // Value of "Login" button. If set, the login button was pressed.
        $this->submitValue = $parsedBody['commandLI'] ?? $queryParams['commandLI'] ?? null;

        // Setting the redirect URL to "index.php?M=main" if no alternative input is given
        if ($this->redirectUrl) {
            $this->redirectToURL = $this->redirectUrl;
        } else {
            // (consolidate RouteDispatcher::evaluateReferrer() when changing 'main' to something different)
            $this->redirectToURL = (string)$this->uriBuilder->buildUriWithRedirect('main', [], RouteRedirect::createFromRequest($request));
        }

        // If "L" is "OUT", then any logged in is logged out. If redirect_url is given, we redirect to it
        if (($parsedBody['L'] ?? $queryParams['L'] ?? null) === 'OUT' && is_object($backendUser)) {
            $backendUser->logoff();
            $this->redirectToUrl();
        }

        // @todo: This should be ViewInterface. But this breaks LoginProviderInterface AND ModifyPageLayoutOnLoginProviderSelectionEvent
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        // StandaloneView should NOT receive a request at all, override the default StandaloneView constructor here.
        $this->view->setRequest();
        $this->view->setTemplateRootPaths(['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Templates']);
        $this->view->setLayoutRootPaths(['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Layouts']);
        $this->view->setPartialRootPaths(['EXT:a11y_backend/Resources/Private/Backend/TemplateOverrides/Partials']);
        $this->provideCustomLoginStyling();
        $this->view->assign('referrerCheckEnabled', $this->features->isFeatureEnabled('security.backend.enforceReferrer'));
        $this->view->assign('loginUrl', (string)$request->getUri());
        $this->view->assign('loginProviderIdentifier', $this->loginProviderIdentifier);
    }
}
