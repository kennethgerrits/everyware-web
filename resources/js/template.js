import {saveData} from "./functions";

let list = $('#wordlist-tablebody');
let selectedId;
let imgdiv = $('#template-img-div');
let oldimgdiv = $('#old-template-img-div');
let questiondiv = $('#template_question_type');
let answerdiv = $('#template_answer_type');
let answers = [];
$(document).ready(function () {
    $('.js-select2').select2();
    $(".js-select2-tags").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })
    $(".js-select2-difficulties").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })
    $('[data-toggle="tooltip"]').tooltip();
    imgdiv.hide();
    $('.info-button').on("click", function (e) {
        e.preventDefault();
        let button = $(this);
        $('#infoModalTitle').text(button.data('header'));
        $('#infoModalText').text(button.data('text'));
    });
    selectedId = $('#template_wordlist_id').val();
    getWordlistPreview();
    $('#template_wordlist_id').on('change', function () {
        selectedId = $(this).val();
        getWordlistPreview();
    });
    $('#image_id').on('change', function () {
        var fileName = getFile($(this).val());
        $(this).next('.custom-file-label').html(fileName);
        readURL(this);
    });
    $.ajax({
        url: $('#template-card').data('answerurl'),
        method: 'get',
        success: function (response) {
            answers = response;
        }
    })
    //Load previews
    handleQuestionTypeIcons();
    handleAnswerTypeIcons();
    // Question Types
    $('#template_question_type').on('change', handleQuestionTypeIcons);
    // Answer Types
    $('#template_answer_type').on('change', handleAnswerTypeIcons);

    $('#btn-save').on('click', async function (e) {
        e.preventDefault();
        if(!await saveData('template')){
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

    $('#template_reward').on('click', openRewardModal);

    $('#reward-save-btn').on('click', concatUrl)
})

function getFile(filePath) {
    return filePath.substr(filePath.lastIndexOf('\\') + 1);
}

function getWordlistPreview() {
    list.empty();
    if (selectedId) {
        $.ajax({
            url: $('#template-card').data('wordlisturl').replace("#1", selectedId),
            method: 'get',
            success: function (response) {
                $.each(response.words, function (key, item) {
                    addItemToList(item);
                })
            }
        })
    }
}

function addItemToList(item) {
    let name = item.name == null || !item.name ? '-' : item.name;
    let url = item.url == null || !item.url ? '-' : item.url;
    if (url === '-') {
        list.append('<tr> <td class="template-td">' + name + ' </td>' + '<td class="template-td"> - </td> </tr>');
    } else {
        list.append('<tr> <td class="template-td">' + name + ' </td>' + '<td class="template-td">' + '<img src="data:image/jpeg;base64, ' + url + '" style="width: 100px" />' + ' </td> </tr>');
    }
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

function handleQuestionTypeIcons() {
    $('.js-preview-question').hide();
    let answerSelect = $('#template_answer_type');
    //EMPTY ANSWERS IF FILTER IS ACTIVE
    if (answerSelect.children().length === 1) {
        answerSelect.empty();
        $.each(answers, function (key, value) {
            answerSelect.append('<option value="' + key + '" selected>' + value + '</option>');
        })
        answerSelect.trigger('change');
    }
    //HIDE MATH INPUTS
    let mathTypes = $('.js-math');
    mathTypes.addClass('d-none');
    //HIDE SUM INPUT
    let sumType = $('.js-sum-type');
    sumType.addClass('d-none');
    switch (questiondiv.val()) {
        case 'STATIC_IMAGE':
            $("#div-preview-image").show();
            break;
        case 'STATIC_TEXT':
            $("#div-preview-text").show();
            break;
        case 'LISTENING':
            $("#div-preview-listening").show();
            break;
        case 'ARITHMETIC_LISTENING':
            $("#div-preview-listening").show();
            sumType.removeClass('d-none');
            mathTypes.removeClass('d-none');
            break;
        case 'ARITHMETIC_IMAGE':
            $("#div-preview-arithmetic-image").show();
            mathTypes.removeClass('d-none');
            break;
        case 'ARITHMETIC_SUM_TEXT':
            $("#div-preview-arithmetic-sum-text").show();
            mathTypes.removeClass('d-none');
            sumType.removeClass('d-none');
            break;
        case 'ARITHMETIC_SUM_IMAGE':
            $("#div-preview-arithmetic-sum-image").show();
            mathTypes.removeClass('d-none');
            sumType.removeClass('d-none');
            break;
        case 'DRAG_IMAGE':
            $("#div-preview-drag-image").show();
            mathTypes.removeClass('d-none');
            //SET DRAG FILTER
            answerSelect.empty();
            answerSelect.append('<option value="DRAG" selected="selected" >Slepen</option>');
            answerSelect.trigger('change');
            break;
    }
}

function handleAnswerTypeIcons() {
    $('.js-preview-answer').hide();
    switch(answerdiv.val()){
        case 'MULTIPLE_CHOICE':
            $("#div-preview-multi").show();
            break;
        case 'WRITING':
            $("#div-preview-writing").show();
            break;
        case 'VOICE':
            $("#div-preview-voice").show();
            break;
    }
}

function reload() {
    $.ajax({
        url: $('#templates-table').data('url'),
        method: 'GET',
        success: function (response) {
            let body = $('#templates-table').find('tbody');
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
    let templateReward = $('#template_reward');
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
    let templateReward = $('#template_reward');
    let modalReward = $('#modal_reward');
    let url = modalReward.val().split('&')[0];
    let start = ($('#start_min').val() * 60) + $('#start_sec').val() * 1;
    if(start != 0) {
        url += "&start=" + start;
    }
    templateReward.val(url);
}
