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

import DocumentService from '@typo3/core/document-service.js'
import FormEngineValidation from '@typo3/backend/form-engine-validation.js'
import $ from 'jquery'

class AriaValidationHints {
  constructor () {
    this.initialize()
  }

  static markedFields = [];

  static addAriaValidationHints () {
    AriaValidationHints.markedFields.forEach(function (elem) {
      elem.setAttribute('aria-invalid', false)
    })
    const invalidFields = AriaValidationHints.findInvalidFields()
    invalidFields.forEach(function (elem) {
      const formElement = elem.querySelector('.form-wizards-element .form-select[data-formengine-input-name], .form-wizards-element .form-control[data-formengine-input-name]')
      AriaValidationHints.markedFields.push(formElement)
      formElement.setAttribute('aria-invalid', true)
    })
  }

  static findMarkedInvalidFields () {
    return document.querySelectorAll('.tab-content .form-wizards-element [data-formengine-input-name][aria-invalid]')
  }

  static findInvalidFields () {
    return document.querySelectorAll('.tab-content .' + FormEngineValidation.errorClass)
  }

  initialize () {
    DocumentService.ready().then(() => {
      AriaValidationHints.addAriaValidationHints()
    })
    // for TYPO3 13 this has to be changed to document.addEventListener
    $(document).on('t3-formengine-postfieldvalidation', AriaValidationHints.addAriaValidationHints)
  }
}

export default new AriaValidationHints()
