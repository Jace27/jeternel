function display_error (error) {
    if (typeof error == 'string') {
        $('#p-modal-error').html(error);
    } else {
        if (error.responseJSON != null) {
            if (error.responseJSON.message != null)
                $('#p-modal-error').html(error.responseJSON.message);
            else
                $('#p-modal-error').html(error.responseText);
        } else {
            $('#p-modal-error').html(error.responseText);
        }
    }
    $('#ErrorModal').modal('show');
}
