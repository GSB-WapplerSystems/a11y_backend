import DocumentService from"@typo3/core/document-service.js";import FormEngineValidation from"@typo3/backend/form-engine-validation.js";import $ from"jquery";class AriaValidationHints{constructor(){this.initialize()}static markedFields=[];static addAriaValidationHints(){AriaValidationHints.markedFields.forEach((function(i){i.setAttribute("aria-invalid",!1)})),AriaValidationHints.findInvalidFields().forEach((function(i){const t=i.querySelector(".form-wizards-element .form-select[data-formengine-input-name], .form-wizards-element .form-control[data-formengine-input-name]");AriaValidationHints.markedFields.push(t),t.setAttribute("aria-invalid",!0)}))}static findMarkedInvalidFields(){return document.querySelectorAll(".tab-content .form-wizards-element [data-formengine-input-name][aria-invalid]")}static findInvalidFields(){return document.querySelectorAll(".tab-content ."+FormEngineValidation.errorClass)}initialize(){DocumentService.ready().then((()=>{AriaValidationHints.addAriaValidationHints()})),$(document).on("t3-formengine-postfieldvalidation",AriaValidationHints.addAriaValidationHints)}}export default new AriaValidationHints;