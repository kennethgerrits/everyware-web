@formStart($form, ['attr' => ['enctype' => 'multipart/form-data', 'id'=>'edit-form', 'data-url' => $route]])
<div class="row row-card" id="template-card" data-answerurl="{{route('templates.answers.get')}}" data-wordlisturl="{{route('templates.wordlist.get', ['id' => '#1' ])}}">
    <div class="col-lg-3 col-sm-3 col-12">
        @formLabel($form['name'], 'Naam werkblad*')
        @formWidget($form['name'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-lg-3 col-sm-3 col-6">
        @formLabel($form['welcome_message'], 'Welkom bericht*')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.welcome')}}" data-text="{{__('information.welcome_message')}}"></button>
        @formWidget($form['welcome_message'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="vertical-center col-lg-2 col-sm-4 col-6">
        @formLabel($form['is_available'], 'Beschikbaarheid')
        @formWidget($form['is_available'], ['attr' => ['class' => 'input-field']]) &nbsp;
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.available')}}" data-text="{{__('information.available_message')}}"></button>
    </div>
    <div class="col-lg-2 d-none d-lg-block text-right">
        @isset($item)
        <button type="button" class="btn btn-link btn-delete" data-toggle="modal" data-target="#copyModal">
            {{__('template.copy_template')}}
        </button>
        @endisset
        <button type="submit" class="btn btn-save" id="btn-save"><i class="fa fa-save"></i></button>
        @isset($item)
        <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
            <i class="fa fa-trash"></i>
        </button>
        @endisset
    </div>
    <div class="col mb-2 col-lg-2">
        @if(isset($image))
        <div id="old-template-img-div">
            <label for="image" class="col-form-label">{{__("template.curr_pic")}}</label> <br>
            <img src="data:image/jpeg;base64, {{$image}}" style="width: 40%" />
        </div>
        @endif
        <div id="template-img-div">
            <label for="image" class="col-form-label">{{__('template.curr_picmessage')}}</label>
            <br>
            <img id="template-img-tag" style="width: 40%" />
        </div>
    </div>
</div>
<div class="row row-card">
    <div class="col-lg-2 col-sm-4 col-12">
        @formLabel($form['tags'], 'Tags')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.tags')}}" data-text="{{__('information.tags_message')}}"></button>
        @formWidget($form['tags'])
    </div>
    <div class="col-lg-2 col-sm-4 col-6">
        @formLabel($form['difficulties'], 'Niveau *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.level')}}" data-text="{{__('information.level_message')}}"></button>
        @formWidget($form['difficulties'])
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        @formLabel($form['question_type'], 'Type vragen *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.type_question')}}" data-text="{{__('information.type_question_message')}}"></button>
        @formWidget($form['question_type'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-lg-3 col-sm-4 col-6">
        @formLabel($form['answer_type'], 'Type beantwoording *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.type_answer')}}" data-text="{{__('information.type_answer_message')}}"></button>
        @formWidget($form['answer_type'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-lg-2 col-sm-6 col-12">
        <label for="image_id">{{__('template.template_picture')}}</label>
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.template_picture')}}" data-text="{{__('information.template_picture_message')}}"></button>
        <div class="custom-file">
            <input data-toggle="tooltip" data-placement="top" title="Aanbevolen: 200x200px transparant bestand." type="file" id="image_id" name="image_id" class="custom-file-input" accept=".png,.jpg,.jpeg">
            <label for="image_id" lang="en" class="custom-file-label input-field"></label>
        </div>
    </div>
</div>
<div class="row row-card">
    <div class="col-2">
        @formLabel($form['question_amount'], 'Aantal vragen *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.num_questions')}}" data-text="{{__('information.num_questions_message')}}"></button>
        @formWidget($form['question_amount'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-2">
        @formLabel($form['cesuur'], 'Cesuur *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.cesuur')}}" data-text="{{__('information.cesuur_message')}}"></button>
        @formWidget($form['cesuur'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-3">
        @formLabel($form['wordlist_id'], 'Woordenlijst')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.word_list')}}" data-text="{{__('information.word_list_message')}}"></button>
        @formWidget($form['wordlist_id'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-3">
        @formLabel($form['reward'], 'Beloning')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.reward')}}" data-text="{{__('information.reward_message')}}"></button>
        @formWidget($form['reward'], ['attr' => ['class' => 'input-field', 'data-target' => '#rewardModal',
        'data-toggle' => 'modal']])
    </div>
    <div class="col-2">
        @formLabel($form['category_id'], 'Categorie *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.category')}}" data-text="{{__('information.category_message')}}"></button>
        @formWidget($form['category_id'], ['attr' => ['class' => 'input-field']])
    </div>
</div>
<div class="row row-card js-math none">
    <div class="col-12 col-sm-4 js-sum-type none">
        @formLabel($form['sum_type'], 'Soort berekening *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.math_type')}}" data-text="{{__('information.math_type_message')}}"></button>
        @formWidget($form['sum_type'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-6 col-sm-4">
        @formLabel($form['min_amount'], 'Minimaal aantal *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.min_amount')}}" data-text="{{__('information.min_amount_message')}}"></button>
        @formWidget($form['min_amount'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-6 col-sm-4">
        @formLabel($form['max_amount'], 'Maximaal aantal *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.max_amount')}}" data-text="{{__('information.max_amount_message')}}"></button>
        @formWidget($form['max_amount'], ['attr' => ['class' => 'input-field']])
    </div>
</div>

<div class="row row-card">
    <div class="col-sm-12 col-lg-6">
        <label for="templates">{{__('template.required_templates')}}</label>
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.required_templates')}}" data-text="{{__('information.required_templates_message')}}"></button>
        <select id="required_templates" name="required_templates[]" class="js-select2" multiple>
            @isset($templates)
            @foreach($templates as $template)
            <option value="{{$template->id}}" @isset($item) @if($item->required_templates) @if(in_array($template->_id, $item->required_templates))
                selected
                @endif
                @endif
                @endisset
                >{{$template->name}}</option>
            @endforeach
            @endisset
        </select>
    </div>
</div>

<div class="d-lg-none d-xl-none">
    @isset($item)
    <button type="button" class="btn btn-link btn-delete" data-toggle="modal" data-target="#copyModal">
        {{__('template.copy_template')}}
    </button>
    @endisset
    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
    @isset($item)
    <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
        <i class="fa fa-trash"></i>
    </button>
    @endisset
</div>
@formEnd($form)

<div class="row" id="div-preview">
    <div class="col-8">
        <div class="card card-shadow mt-3">
            <div class="card-body" id="live-preview">
                <!-- Template Question types -->
                <!-- Image -->
                <div class="row row-card js-preview-question" id="div-preview-image">
                    <div class="col d-flex justify-content-center">
                        <img src="{{asset('images/imgpreview.png')}}" />
                    </div>
                </div>

                <!-- Text -->
                <div class="row row-card js-preview-question" id="div-preview-text">
                    <div class="col d-flex justify-content-center">
                        <img src="{{asset('images/text-icon.png')}}" />
                    </div>
                </div>
                <!-- Listening -->
                <div class="row row-card js-preview-question" id="div-preview-listening">
                    <div class="col d-flex justify-content-center">
                        <img src="{{asset('images/listening-icon.png')}}" />
                    </div>
                </div>
                <!-- ARITHMETIC_IMAGE -->
                <div class="row row-card js-preview-question" id="div-preview-arithmetic-image">
                    <div class="col-12 d-flex justify-content-around">
                        <img src="{{asset('images/arithmatic-image-icon.png')}}" class="preview-image" />
                        <img src="{{asset('images/arithmatic-image-icon.png')}}" class="preview-image" />
                        <img src="{{asset('images/arithmatic-image-icon.png')}}" class="preview-image" />
                    </div>
                </div>

                <!-- ARITHMETIC_SUM_TEXT -->
                <div class="row row-card js-preview-question" id="div-preview-arithmetic-sum-text">
                    <p class="col-12 text-center preview-text">
                        A + B = ?
                    </p>
                </div>

                <!-- ARITHMETIC_SUM_IMAGE -->
                <div class="row row-card js-preview-question" id="div-preview-arithmetic-sum-image">
                    <div class="col-12 d-flex justify-content-around">
                        <img src="{{asset('images/arithmatic-image-icon.png')}}" class="preview-image" />
                        <p class="preview-text">+</p>
                        <img src="{{asset('images/arithmatic-image-icon.png')}}" class="preview-image" />
                        <p class="preview-text">= ?</p>
                    </div>
                </div>

                <!-- DRAG_IMAGE -->
                <div class="row row-card js-preview-question" id="div-preview-drag-image">
                    <div class="col-12 d-flex justify-content-around">
                        <img src="{{asset('images/icon-drag.jpg')}}" class="preview-image" />
                    </div>
                </div>

                <!-- Template Answer types -->
                <!-- Multiple choice -->
                <div class="row row-card js-preview-answer" id="div-preview-multi">
                    <div class="col">
                        <div class="preview-bottom d-flex justify-content-center">
                            <span class="fa fa-check"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="preview-bottom d-flex justify-content-center">
                            <span class="fa fa-times"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="preview-bottom d-flex justify-content-center">
                            <span class="fa fa-times"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="preview-bottom d-flex justify-content-center">
                            <span class="fa fa-times"></span>
                        </div>
                    </div>
                </div>

                <!-- Writing -->
                <div class="row row-card js-preview-answer" id="div-preview-writing">
                    <div class="col d-flex justify-content-center">
                        <img src="{{asset('images/writing-icon.png')}}" />
                    </div>
                </div>

                <!-- Voice -->
                <div class="row row-card js-preview-answer" id="div-preview-voice">
                    <div class="col d-flex justify-content-center">
                        <img src="{{asset('images/voice-icon.png')}}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">

        <div class="card card-shadow mt-3">
            <div class="card-body">
                <div class="col" id="wordlist-list">
                    <table class="table template-table">
                        <thead>
                            <tr>
                                <th scope="col" class="template-th">{{__('template.word')}}</th>
                                <th scope="col" class="template-th">{{__('template.photo')}}</th>
                            </tr>
                        </thead>
                        <tbody id='wordlist-tablebody'>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
                {{__('general.delete_message', ['itemname' => $item->name])}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.no')}}</button>
                <form method="POST" action="{{ route('templates.destroy', $item->id) }}" id="delete-form">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="{{__('general.delete')}}" class="btn btn-danger" id="btn-delete" />
                </form>
            </div>
        </div>
    </div>
</div>
@endisset
<!-- End Modal -->

<!-- Copy Modal -->
@isset($item)
<div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="copyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copyModalLabel">{{__('template.make_copy')}} {{$item->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('templates.copy.post', $item->id) }}" id="copy-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <p>{{__('template.enter_name_copy')}}</p>
                        <label for="name">{{__('template.name_copy')}}</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{$item->name}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.cancel')}}</button>
                    <input type="hidden" name="_method" value="POST">
                    <input type="submit" value="Kopiëren" class="btn btn-primary" id="btn-copy" />
                </div>
            </form>
        </div>
    </div>
</div>
@endisset
<!-- End Modal -->

<!-- Rewards Modal -->
@include('templates.partials.reward')
<!-- End Rewards Modal -->

@include('templates.partials.info')
