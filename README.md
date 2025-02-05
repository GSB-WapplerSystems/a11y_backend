<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

<!-- PROJECT SHIELDS -->
[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)

# GSB&nbsp;11 Extension a11y_backend


## About
The extension a11y_backend contains several enhancements of the accessibility of the editorial backend. It is part of the Government Side Builder (GSB) 11.

[Learn more about the GSB&nbsp;11][gsb11-readme-url].


## Installation
The best way to install this extension is to start with the [GSB Sitepackage Kickstarter][kickstarter-url] extension.

## Quick installation without GSB Sitepackage Kickstarter
In a composer-based TYPO3 installation you can install the extension EXT:a11y_backend via composer:

```sh
composer config -g gitlab-domains gitlab.opencode.de && \
composer config -g repositories.a11y-backend vcs https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/a11y_backend.git
```

```sh
composer require itzbund/a11y-backend
```

In TYPO3 installations above version 11.5 the extension will be automatically installed. You do not have to activate it manually.

## Usage
Nothing to do. Just include the extension and your backend is better than before ✨.

## Contribute
As with TYPO3, we encourage you to join the project by submitting changes. Development of the GSB&nbsp;11 happens mainly in the GSB&nbsp;11 TYPO3 extension repositories.

To get started, have a look at our [detailed contribution walkthrough](https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gitlab-profile/-/blob/main/CONTRIBUTING.md).


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[a11y-project-url]: https://www.a11yproject.com/
[kickstarter-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gsb-sitepackage-kickstarter
[gsb11-readme-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions
