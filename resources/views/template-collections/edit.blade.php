@formStart($form, ['attr' => ['enctype' => 'multipart/form-data', 'id' => 'edit-form', 'data-url' => $route]])
<div class="row row-card">
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
    <div class="col-lg-4 col-sm-4 col-12">
        @formLabel($form['tags'], 'Tags')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="Tags" data-text="{{__('information.tags_message')}}"></button>
        @formWidget($form['tags'], ['attr' => ['class' => 'input-field js-select2-tags']])
    </div>
    <div class="col-lg-4 col-sm-4 col-6">
        @formLabel($form['difficulties'], 'Niveau *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.level')}}" data-text="{{__('information.level_message')}}"></button>
        @formWidget($form['difficulties'], ['attr' => ['class' => 'input-field js-select2-difficulties']])
    </div>
    <div class="col-lg-4 col-sm-4 col-12">
        <label for="image_id">{{__('template.template_picture')}}</label>
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.type_answer')}}" data-text="{{__('information.type_answer_message')}}"></button>
        <div class="custom-file">
            <input type="file" id="image_id" name="image_id" class="custom-file-input" accept=".png,.jpg,.jpeg">
            <label for="image_id" lang="en" class="custom-file-label input-field"></label>
        </div>
    </div>
</div>
<div class="row row-card">
    <div class="col-3">
        @formLabel($form['question_amount'], 'Aantal vragen*')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.num_questions')}}" data-text="{{__('information.num_questions_message')}}"></button>
        @formWidget($form['question_amount'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-3">
        @formLabel($form['cesuur'], 'Cesuur*')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.cesuur')}}" data-text="{{__('information.cesuur_message')}}"></button>
        @formWidget($form['cesuur'], ['attr' => ['class' => 'input-field']])
    </div>
    <div class="col-3">
        @formLabel($form['reward'], 'Beloning')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.reward')}}" data-text="{{__('information.reward_message')}}"></button>
        @formWidget($form['reward'], ['attr' => ['class' => 'input-field', 'data-target' =>
        '#rewardModal', 'data-toggle' => 'modal']])
    </div>
    <div class="col-3">
        @formLabel($form['category_id'], 'Categorie *')
        <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.category')}}" data-text="{{__('information.category_message')}}"></button>
        @formWidget($form['category_id'], ['attr' => ['class' => 'input-field']])
    </div>
</div>
<div class="d-lg-none d-xl-none">
    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
    @isset($item)
    <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
        <i class="fa fa-trash"></i>
    </button>
    @endisset
</div>

<div class="w-50 mt-5">
    <div class="d-flex justify-content-around">
        <div class="form-group">
            <h4>{{__('template.worksheet_collection')}}</h4>
            <select multiple name="templates[]" id="existing-templates" class="form-control" style="min-height: 400px">
                @isset($existingTemplates)
                @foreach($existingTemplates as $name => $id)
                <option value="{{$id}}">{{$name}}</option>
                @endforeach
                @endisset
            </select>
        </div>
        <div class="d-flex flex-column justify-content-center">
            <button class="btn btn-primary mb-2" id="right-arrow"><i class="fa fa-arrow-right"></i></button>
            <button class="btn btn-primary" id="left-arrow"><i class="fa fa-arrow-left"></i></button>
        </div>
        <div class="form-group">
            <h4>{{__('template.available_worksheets')}}</h4>
            <select multiple id="available-templates" class="form-control" style="min-height: 400px">
                @foreach($availableTemplates as $name => $id)
                <option value="{{$id}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex flex-column">
            <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal" data-header="{{__('information.combined_worksheets')}}" data-text="{{__('information.combined_worksheets_message')}}"></button>
        </div>
    </div>
</div>
@formEnd($form)


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
                <form method="POST" action="{{ route('template-collections.destroy', $item->id) }}" id="delete-form">
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
<!-- Rewards Modal -->
@include('templates.partials.reward')
<!-- End Rewards Modal -->

@include('template-collections.partials.info')
