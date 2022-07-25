@if(session()->has('error'))
<div class="alert alert-danger">{{session()->get('error')}}</div><br>
@endif

@formStart($form, ['attr' => ['enctype' => 'multipart/form-data', 'id' => 'edit-form', 'data-url' => $route]])
<div class="row row-card">
    <div class="col-lg-4 col-sm-8 col-12">
        @formRow($form['name'], ['attr' => ['class' => 'input-field']])
    </div>

    <div class="col-lg-4">
        <label for="image_id">{{__('category.photo_category')}}</label>
        <div class="custom-file">
            <input data-toggle="tooltip" data-placement="top" title="Aanbevolen: 200x200px transparant bestand." type="file" id="image_id" name="image_id" class="custom-file-input" accept=".png,.jpg,.jpeg">
            <label for="image_id" lang="en" class="custom-file-label input-field"></label>
        </div>
    </div>

    <div class="col-lg-1 col-sm-1 col-12 color-picker">
        @formRow($form['color'], ['attr' => ['class' => 'input-field']])
    </div>

    <div class="col-lg-1 col-sm-3">
        <button class="btn btn-save" id="btn-save"><i class="fa fa-save"></i></button>
        @isset($item)
        <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
            <i class="fa fa-trash"></i>
        </button>
        @endisset
    </div>

    <div class="col mb-2 col-lg-2">
        @if(isset($image))
        <div id="old-template-img-div">
            <label for="image" class="col-form-label">{{__('category.photo_category')}}</label> <br>
            <img src="data:image/jpeg;base64, {{$image}}" style="width: 70%" />
        </div>
        @endif
        <div id="template-img-div">
            <label for="image" class="col-form-label">{{__('category.photo_category_not_saved')}}</label>
            <br>
            <img id="template-img-tag" style="width: 70%" />
        </div>
    </div>
</div>
@formWidget($form)
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
                <form method="POST" action="{{ route('category.destroy', $item->id) }}" id="delete-form">
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
