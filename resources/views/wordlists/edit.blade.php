@extends('layouts.app')
@push('body')
<div class="container">
    @if(session()->has('error'))
    <div class="alert alert-danger">{{session()->get('error')}}</div><br>
    @endif

    @formStart($form, ['attr' => ['enctype' => 'multipart/form-data']])
    <div class="card card-shadow mb-3">
        <div class="card-body">
            <div class="row row-card">
                <div class="col-lg-11 col-sm-9 col-12">
                    @formRow($form['name'], ['attr' => ['class' => 'input-field']])
                </div>

                <div class="col-lg-1 col-sm-3">
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
                    @isset($item)
                    <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endisset
                </div>
            </div>
            @formWidget($form)
        </div>
        <div class="card-footer" id="dropzone">
            <div class="js-list js-words row"></div>
            <div class="js-list js-word-image row"></div>
            <div class="js-list js-image-list row"></div>
            <br />
            <button data-toggle="tooltip" data-placement="top" title="Aanbevolen: 200x200px transparant bestand." class="btn btn-secondary form-group" id="btn-new-word">{{__('wordlist.add')}} <i class="fa fa-plus"></i></button>
            <button data-toggle="tooltip" data-placement="top" title="Aanbevolen: 200x200px transparant bestand." class="btn btn-secondary form-group" id="btn-new-multiple-images">{{__('wordlist.add_multiple')}} <i class="fa fa-plus"></i></button>
        </div>
    </div>

    <input type="hidden" value="" name="wordlist[deleted_images]" id="deleted_images">
    @formEnd($form)
</div>

<div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-6 js-item-template d-none js-item-template">
    <div class="d-flex flex-column justify-content-around align-items-center mr-2">
        <img style="width: auto; max-height: 100px" />
        <input name="wordlist[words][]" type="text" class="form-control" placeholder="Naam" disabled>
        <input name="wordlist[files][]" type="file" class="form-control" accept=".png,.jpg,.jpeg" disabled>
    </div>
    <button class="btn btn-delete js-template-btn"> <i class="fa fa-trash js-template-icon"></i></button>
</div>

<input onchange="readURL(this)" type="file" class="form-control" accept=".png,.jpg,.jpeg" multiple hidden id="word-imgs">

<!-- Delete Modal -->
@isset($item)
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{__('general.confirm_delete')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{__('general.delete_message', ['itemname' =>$item->name])}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.no')}}</button>
                <form method="POST" action="{{ route('wordlists.destroy', $item->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="{{__('general.delete')}}" class="btn btn-danger" />
                </form>
            </div>
        </div>
    </div>
</div>
@endisset
<!-- End Modal -->
@endpush

@section('scripts')
<script type="text/javascript">
    let list = $('.js-words');
    let currentType = '{{$type}}';
    let currentList = '';

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip()

        if (currentType !== '') {
            showList(currentType)
        }

        $('#wordlist_type').on('change', function() {
            currentType = $(this).val();
        });

        $('form[name="wordlist"]').on('submit', function(e) {
            validateFields(e);
        })

        $('#btn-new-word').on('click', function(e) {
            e.preventDefault();

            addSingleItemToList();
        })

        $('#btn-new-multiple-images').on('click', function(e) {
            e.preventDefault();

            $('#word-imgs').click();
        })

        // Setup the Drag n' Drop listeners.
        var dropZone = document.getElementById('dropzone');
        dropZone.addEventListener('dragover', handleDragOver, false);
        dropZone.addEventListener('drop', handleFileUpload, false);

        @if($id != 0)
        getImages();
        @endif
    })

    function getImages() {
        $.ajax({
            url: "{{route('wordlists.images.get', ['id' => $id])}}",
            method: 'get',
            success: function(response) {
                $.each(response, function(key, item) {
                    @if($type == \App\ Enums\ WordlistType::TEXT)
                    addItemToList(item);
                    @endif
                    @if($type == \App\ Enums\ WordlistType::TEXT_IMAGE)
                    addItemToList(item.related_to, item.src, item.alt, item._id);
                    @endif
                    @if($type == \App\ Enums\ WordlistType::IMAGE)
                    addItemToList('new', item.src, item.alt, item._id);
                    @endif
                })
            }
        })
    }

    $('.js-list').on('click', function(e) {
        //Get clicked object
        let object = $(e.target);
        //Check if it is an icon
        if (object.hasClass('js-template-icon')) {
            object = object.parent();
        }

        //If it is a button, delete the current row
        if (object.hasClass('js-template-btn')) {
            e.preventDefault();
            let toBeDeleted = object.parent();
            if (toBeDeleted.hasClass('js-image')) {
                let deletedList = $('#deleted_images').val();

                if (deletedList !== "") {
                    deletedList += ',' + toBeDeleted.data('id');
                } else {
                    deletedList = toBeDeleted.data('id');
                }
                $('#deleted_images').val(deletedList);
            }
            toBeDeleted.remove();
        }
    })

    function addItemToList(item = '', img_path = '', alt = '', id = 0) {
        if (currentType === "TEXT") {
            list.append('<div class="form-group d-flex col-lg-2 col-sm-4 col-6"><input data-fieldtype="name" name="wordlist[words][]" type="text" class="form-control input-field" placeholder="Naam" value="' + item + '"><button class="btn btn-delete js-template-btn"><i class="fa fa-trash js-template-icon"></i></button></div>');
        } else if (currentType === "TEXT_IMAGE") {
            list.append('<div class="form-group d-flex js-image col-xl-2 col-lg-3 col-md-4 col-sm-6" data-id="' + id + '"><div class="d-flex flex-column justify-content-around align-items-center mr-2"><img src="data:image/jpeg;base64, ' + img_path + '" alt="' + alt + '" style="width: auto; height: 100px" /><input data-fieldtype="name" name="wordlist[existing_words][' + id + ']" type="text" class="form-control mr-2 input-field" placeholder="Naam" value="' + item + '"></div><button class="btn btn-delete js-template-btn"> <i class="fa fa-trash js-template-icon"></i></button></div>');
        } else if (currentType === "IMAGE") {
            list.append('<div class="form-group d-flex js-image col-lg-3 col-sm-4" data-id="' + id + '"><div class="d-flex flex-column justify-content-around align-items-center mr-2"><img src="data:image/jpeg;base64, ' + img_path + '" alt="' + alt + '" style="max-width: 100px; height: auto"/></div><button class="btn btn-delete js-template-btn"> <i class="fa fa-trash js-template-icon"></i></button></div>');
        }
    }

    function addSingleItemToList() {
        list.append('<div class="form-group d-flex col-xl-2 col-lg-3 col-md-4 col-sm-6"><div class="d-flex flex-column justify-content-around align-items-center mr-2"><input data-fieldtype="file" onchange="readURL(this,true)" name="wordlist[files][]" type="file" class="form-control input-field" accept=".png,.jpg,.jpeg" ><input data-fieldtype="name" name="wordlist[words][]" type="text" class="form-control input-field" placeholder="Naam"></div><button class="btn btn-delete js-template-btn"> <i class="fa fa-trash js-template-icon"></i></button></div>')
    }

    function showList(type) {
        $('.js-list').hide();
        list.empty();
        if (type === "TEXT") {
            $('.js-words').show();
            list = $('.js-words');
            currentList = '.js-word';
        } else if (type === "TEXT_IMAGE") {
            $('.js-word-image').show();
            list = $('.js-word-image');
            currentList = '.js-word-image';
        } else if (type === "IMAGE") {
            $('.js-image-list').show();
            list = $('.js-image-list');
            currentList = '.js-image-list';
        }
    }

    // DROP ZONE
    function handleFileUpload(e) {
        e.stopPropagation();
        e.preventDefault();
        let files = e.dataTransfer.files;
        let valid = ['image/png', 'image/jpg', 'image/jpeg'];

        for (var i = 0; i < files.length; i++) {
            if (valid.includes(files[i].type)) {
                appendWordImage(files, i);
            }
        }
    }

    function handleDragOver(e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    }

    function readURL(input, single) {
        let valid = ['image/png', 'image/jpg', 'image/jpeg'];
        let files = input.files;
        if (input.files && input.files[0]) {
            for (var i = 0; i < input.files.length; i++) {
                if (valid.includes(files[i].type)) {
                    appendWordImage(files, i);
                }
            }
        }
        input.value = "";

        if (single) {
            $(input).parent().parent().remove();
        }
    }

    function appendWordImage(files, i) {
        let dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[i]);

        let element = $('.js-item-template').clone();
        element.removeClass('d-none js-item-template');
        element.addClass('d-flex');
        let text = element.children().find('input[name="wordlist[words][]"]');
        text.attr('data-fieldtype', 'name');
        let file = element.children().find('input[name="wordlist[files][]"]');
        file.attr('data-fieldtype', 'file');
        let image = element.children().find('img');

        file.prop('disabled', false);
        text.prop('disabled', false);

        file.hide();

        text.val(files[i].name.substr(0, files[i].name.indexOf('.')));
        file.prop("files", dataTransfer.files);

        var reader = new FileReader();
        reader.onload = function(e) {
            $(image).attr('src', e.target.result);
        };
        reader.readAsDataURL(files[i]);

        list.append(element);
    }

    function validateFields(e) {
        if (currentType === "TEXT_IMAGE") {
            checkText(e);
            checkImage(e);
        } else if (currentType === 'TEXT') {
            checkText(e);
        } else if (currentType === 'IMAGE') {
            checkImage(e);
        } else {
            checkText(e);
            checkImage(e);
        }
    }

    function checkText(e) {
        if (typeof $('*[data-fieldtype="name"]') === 'undefined') {
            e.preventDefault();
        }

        $('*[data-fieldtype="name"]').each(function() {
            var input = $(this);
            if (input.val().length === 0) {
                input.addClass('error-field');

                // cancel submit
                e.preventDefault();
            } else {
                input.removeClass('error-field');
            }
        })
    }

    function checkImage(e) {
        if (typeof $('*[data-fieldtype="file"]') === 'undefined') {
            e.preventDefault();
        }

        $('*[data-fieldtype="file"]').each(function() {
            var input = $(this);
            if (input.val().length === 0) {
                input.addClass('error-field');

                // cancel submit
                e.preventDefault();
            } else {
                input.removeClass('error-field');
            }
        })
    }
</script>
@endsection
