@extends('layouts.app')
@push('body')
<div class="container">
    @if(session()->has('error'))
    <div class="alert alert-danger">{{session()->get('error')}}</div><br>
    @endif
    @formStart($form)
    <div class="card card-shadow mb-3">
        <div class="card-body">
            <div class="row row-card mb-2">
                <div class="col-6 col-lg-5">
                    @formLabel($form['name'], 'Naam *')
                    @formWidget($form['name'], ['attr' => ['class' => 'input-field']])
                </div>
                <div class="col-6 col-lg-5">
                    <label for="teachers">Leraren</label>
                    <select id="teachers" name="teachers[]" class="js-select2" multiple>
                        @isset($teachers)
                        @foreach($teachers as $user)
                        <option value="{{$user->id}}" @isset($item) @if($user->teachergroup_ids) @if(in_array($item->id, $user->teachergroup_ids))
                            selected
                            @endif
                            @endif
                            @endisset
                            >{{$user->name}}</option>
                        @endforeach
                        @endisset
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-none d-lg-block">
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
                    @isset($item)
                    <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#deleteModal">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endisset
                </div>
            </div>
            <div class="row row-card">
                <div class="col-sm-12 col-lg-6">
                    @formLabel($form['templates'], 'Werkbladen')
                    @formWidget($form['templates'])
                </div>
                <div class="col-sm-12 col-lg-6">
                    <label for="users">Leerlingen</label>
                    <select id="users" name="users[]" class="js-select2" multiple>
                        @isset($users)
                        @foreach($users as $user)
                        <option value="{{$user->id}}" @isset($item) @if($user->studentgroup_ids) @if(in_array($item->id, $user->studentgroup_ids))
                            selected
                            @endif
                            @endif
                            @endisset
                            >{{$user->name}}</option>
                        @endforeach
                        @endisset
                    </select>
                </div>

                <div class="col-12 d-lg-none d-xl-none">
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
                    @isset($item)
                    <button class="btn btn-delete"><i class="fa fa-trash"></i></button>
                    @endisset
                </div>
            </div>
        </div>
    </div>
    @formEnd($form)
</div>
<!-- Delete Modal -->
@isset($item)
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Bevestig verwijderen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Je staat op het punt om {{$item->name}} te verwijderen. Wil je dat?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Nee</button>
                <form method="POST" action="{{ route('class-groups.destroy', $item->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Verwijderen" class="btn btn-danger" />
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
    $(document).ready(function() {
        $('.js-select2').select2();
    });
</script>
@endsection