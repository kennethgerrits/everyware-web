import {saveData} from "./functions";

let imgdiv = $('#template-img-div');
let oldimgdiv = $('#old-template-img-div');

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip()
    imgdiv.hide();

    $('#image_id').on('change', function () {
        var fileName = getFile($(this).val());
        $(this).next('.custom-file-label').html(fileName);

        readURL(this);
    });

    $('#btn-save').on('click', async function (e) {
        e.preventDefault();
        // console.log(e);
        if(!await saveData('category')){
            reload();
        }
    });

    $('#btn-delete').on('click', function (e) {
        e.preventDefault();
        $.post($('#delete-form').prop('action'), $('#delete-form').serialize());
        $('.modal-backdrop').remove();
        reload();
    });
});

function getFile(filePath) {
    return filePath.substr(filePath.lastIndexOf('\\') + 1);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        imgdiv.show();
        oldimgdiv.hide();

        var reader = new FileReader();

        reader.onload = function (e) {
            $('#template-img-tag').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        imgdiv.hide();
        oldimgdiv.show();
    }
}

function reload() {
    $.ajax({
        url: $('#categories-table').data('url'),
        method: 'GET',
        success: function (response) {
            let body = $('#categories-table').find('tbody');
            body.empty();
            $.each(response, function (val, item) {
                let tr = '<tr data-url="'+item.url+'" data-script="'+item.script+'">' +
                    '<td>'+item.name+'</td></tr>';
                body.append(tr);
            });
        }
    })
    $('.tab-pane').removeClass('active');
    $('#crud-body').empty();
    $('.last-pane').tab('show');
}
