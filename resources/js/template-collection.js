import {saveData} from "./functions";

let right = $('#right-arrow');
let left = $('#left-arrow');
let existingList = $('#existing-templates');
let availableList = $('#available-templates');
let imgdiv = $('#template-img-div');
let oldimgdiv = $('#old-template-img-div');

$(document).ready(function () {

    $('.info-button').on("click", function (e) {
        e.preventDefault();
        let button = $(this);
        $('#infoModalTitle').text(button.data('header'));
        $('#infoModalText').text(button.data('text'));
    });
    $('.js-select2').select2();

    $(".js-select2-tags").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })

    $(".js-select2-difficulties").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })

    left.on('click', function (e) {
        e.preventDefault();
        let options = availableList.children("option:selected");
        existingList.append(options);
    });
    right.on('click', function (e) {
        e.preventDefault();
        let options = existingList.children("option:selected");
        availableList.append(options);
    });

    imgdiv.hide();

    $('#image_id').on('change', function () {
        var fileName = getFile($(this).val());
        $(this).next('.custom-file-label').html(fileName);

        readURL(this);
    });

    $('#btn-save').on('click', async function (e) {
        e.preventDefault();
        existingList.children().prop('selected', true);
        if(!await saveData('templatecollection')){
            reload();
        }
    });

    $('#btn-delete').on('click', function (e) {
        e.preventDefault();
        $.post($('#delete-form').prop('action'), $('#delete-form').serialize());
        $('.modal-backdrop').remove();
        reload();
    });

    $('#btn-copy').on('click', function (e) {
        e.preventDefault();
        $.post($('#copy-form').prop('action'), $('#copy-form').serialize());
        $('.modal-backdrop').remove();
        reload();
    });

    $('#templatecollection_reward').on('click', openRewardModal);

    $('#reward-save-btn').on('click', concatUrl)
})

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
        url: $('#template-collections-table').data('url'),
        method: 'GET',
        success: function (response) {
            let body = $('#template-collections-table').find('tbody');
            body.empty();
            $.each(response, function (val, item) {
                let tr = '<tr data-url="' + item.url + '" data-script="' + item.script + '">' +
                    '<td>' + item.name + '</td>' +
                    '<td>' + item.text_tags + '</td>' +
                    '<td>' + (item.is_available ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') + '</td>' +
                    '<td>' + item.updated_at + '</td>' +
                    '<td>' + item.editedBy + '</td></tr>';
                body.append(tr);
            });
        }
    })
    $('.tab-pane').removeClass('active');
    $('#crud-body').empty();
    $('.last-pane').tab('show');
}

function openRewardModal() {
    let templateReward = $('#templatecollection_reward');
    let modalReward = $('#modal_reward');
    modalReward.val(templateReward.val().split('&')[0]);

    if(templateReward.val().includes('start=')){
        let ptrn = /(start=)\d*/;
        let time = templateReward.val().match(ptrn)[0].replace('start=', '');
        let sec = time % 60;
        let min = (time - sec) / 60;
        $('#start_min').val(min);
        $('#start_sec').val(sec);
    }
}

function concatUrl() {
    let templateReward = $('#templatecollection_reward');
    let modalReward = $('#modal_reward');
    let url = modalReward.val().split('&')[0];
    let start = ($('#start_min').val() * 60) + $('#start_sec').val() * 1;
    if(start != 0) {
        url += "&start=" + start;
    }
    templateReward.val(url);
}
