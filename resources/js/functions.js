export async function saveData(model){
    let hasError = false;
    let form = $('#edit-form');
    let formData = new FormData(form[0]);
    await $.ajax({
        url: $('#edit-form').data('url'),
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        error: function (response) {
            $(document).find('.invalid-feedback').remove();
            hasError = true;
            let errors = response.responseJSON.errors;
            let error = '<span class="invalid-feedback d-block"><span class="d-block">' +
                '                    <span class="form-error-icon badge badge-danger text-uppercase">Error</span> <span class="form-error-message">#</span>' +
                '                </span></span>'
            $.each(errors, function (key, val) {
                $('label[for="'+model+'_'+ key +'"]').append(error.replace('#', val[0]));
            })
        }
    });
    return hasError;
}
