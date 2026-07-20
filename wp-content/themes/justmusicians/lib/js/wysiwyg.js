document.addEventListener('htmx:configRequest', function(event) {
    var form = event.detail.elt;
    if (form && form.querySelector && form.querySelector('textarea[name="description"]')) {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
            var desc = form.querySelector('textarea[name="description"]');
            if (desc) event.detail.parameters['description'] = desc.value;
        }
    }
});
