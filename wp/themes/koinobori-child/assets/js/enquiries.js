/** Preserve the Fluent Forms label on the Choices.js replacement control. */
(function () {
  'use strict';

  function labelChoice(choice) {
    var select = choice.querySelector('select[id]');
    if (!select) return;

    var labelledBy = select.getAttribute('aria-labelledby');
    if (!labelledBy) return;

    choice.setAttribute('aria-labelledby', labelledBy);

    var input = choice.querySelector('.choices__input--cloned');
    if (input) input.setAttribute('aria-labelledby', labelledBy);

    var listbox = choice.querySelector('[role="listbox"]');
    if (listbox) {
      if (!listbox.id) listbox.id = select.id + '-listbox';
      choice.setAttribute('aria-controls', listbox.id);
      if (input) input.setAttribute('aria-controls', listbox.id);
    }
  }

  function labelChoices(root) {
    root.querySelectorAll('.kh-enquiry-form .choices[role="combobox"]').forEach(labelChoice);
  }

  labelChoices(document);

  var observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (node) {
        if (node.nodeType !== 1) return;
        if (node.matches && node.matches('.kh-enquiry-form .choices[role="combobox"]')) labelChoice(node);
        labelChoices(node);
      });
    });
  });

  observer.observe(document.documentElement, { childList: true, subtree: true });
  window.addEventListener('load', function () { labelChoices(document); }, { once: true });
}());
