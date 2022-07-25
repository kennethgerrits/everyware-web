@extends('layouts.app')
@push('body')
<div class="container">
    @formStart($form)
    <div class="card card-shadow mb-3">
        <div class="card-body">
            <div class="row row-card mb-2">
                <div class="col-6 col-lg-5">
                    @formRow($form['first_name'], ['attr' => ['class' => 'input-field']])
                </div>
                <div class="col-6 col-lg-5">
                    @formRow($form['last_name'], ['attr' => ['class' => 'input-field']])
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
                <div class="col-sm-12 col-lg-4">
                    @formRow($form['email'], ['attr' => ['class' => 'input-field']])
                </div>
                <div class="col-sm-6 col-lg-4">
                    @formRow($form['password'], ['attr' => ['class' => 'input-field']])
                </div>
                <div class="col-sm-6 col-lg-4">
                    @formRow($form['password_confirmation'], ['attr' => ['class' => 'input-field']])
                </div>
                <div class="col-12 col-md-6">
                    @formRow($form['roles'])
                </div>
                <div class="col-12 col-md-6">
                    @formRow($form['departments'])
                </div>
                <div class="col-12 col-md-6" id="classgroup_ids">
                    @formRow($form['studentgroup_ids'])
                </div>

                <div class="col-12 d-lg-none d-xl-none">
                    <button type="submit" class="btn btn-save"><i class="fa fa-save"></i></button>
                    <button class="btn btn-delete"><i class="fa fa-trash"></i></button>
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
                <form method="POST" action="{{ route('users.destroy', $item->id) }}">
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
    let student_option = $("option[value='STUDENT']");
    let teacher_option = $("option[value='TEACHER']");
    let admin_option = $("option[value='ADMIN']");

    $(document).ready(function() {
        $('.js-select2').select2();
        $(".js-select2-departments").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })

        showClassGroup();

        $('#user_roles').on('change', function() {
            showClassGroup();
            setRoles();
        });

        student_option.on('click', function() {
        })

        setRoles();

    });

    function setRoles() {
        let select = $('#user_roles');
        if (select.val().includes("STUDENT")) {
            student_option.attr("disabled", false);
            teacher_option.attr("disabled", true);
            admin_option.attr("disabled", true);
        } else if (select.val().includes("TEACHER") || select.val().includes("ADMIN")) {
            student_option.attr("disabled", true);
            teacher_option.attr("disabled", false);
            admin_option.attr("disabled", false);
        } else {
            student_option.attr("disabled", false);
            teacher_option.attr("disabled", false);
            admin_option.attr("disabled", false);
        }
    }

    function showClassGroup() {
        if (student_option.prop('selected')) {
            $('#user_classgroup_ids').prop('disabled', false);
            $('#classgroup_ids').show();
        } else {
            $('#user_classgroup_ids').prop('disabled', true);
            $('#classgroup_ids').hide();
        }
    }
</script>
@endsection
