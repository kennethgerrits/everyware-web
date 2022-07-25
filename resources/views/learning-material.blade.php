@extends('layouts.app')
@push('body')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link active" aria-current="page" data-toggle="tab" data-target="#templates" id="templates-tab" aria-selected="false">{{__(('pages.templates'))}}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" aria-current="page" data-toggle="tab" data-target="#collections" id="collections-tab" aria-selected="true">{{__(('pages.templates_combi'))}}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" aria-current="page" data-toggle="tab" data-target="#categories" id="categories-tab" aria-selected="true">{{__(('pages.category'))}}</button>
                </li>
            </ul>
            <div class="tab-content" id="tabs">
                <div class="tab-pane" id="collections" role="tabpanel" aria-labelledby="collections-tab">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <h5 class="col-sm-9 col-8 card-title">{{__(('pages.templates_combi'))}}</h5>
                                <input placeholder="{{__(('pages.filter_tags'))}}" class="col-sm-2 col-2 form-control card-title" id="search-collections" type="text" />
                                <button class="col-sm-1 col-1 btn btn-save js-partial-create" data-url="{{route('template-collections.create')}}" data-script="{{ asset('js/template-collection.js') }}"><i class="fa fa-plus"></i></button>
                                <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                            </div>
                            <div class="w-100">
                                @isset($collections)
                                <table class="table table-responsive-md js-partial-table" id="template-collections-table" data-url="{{route('material.template-collections.get')}}">
                                    <thead>
                                        <tr>
                                            <th>{{__(('pages.name'))}}</th>
                                            <th>Tags</th>
                                            <th>{{__(('pages.available'))}}</th>
                                            <th>{{__(('pages.latest_alteration'))}}</th>
                                            <th>{{__(('pages.editor'))}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="material-collections-tbody">
                                        @foreach($collections as $template)
                                        <tr data-url="{{route('template-collections.edit', ['template_collection' => $template->id])}}" data-script="{{ asset('js/template-collection.js') }}">
                                            <td>{{$template->name}}</td>
                                            <td>{{$template->text_tags}}</td>
                                            <td>@if($template->is_available)<i class="fa fa-check"></i>@else<i class="fa fa-times"></i>@endif</td>
                                            <td>{{$template->updated_at}}</td>
                                            @if($template->editedBy)
                                            <td>{{$template->editedBy->getNameAttribute()}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show active" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <h5 class="col-sm-9 col-8 card-title">{{__(('pages.templates'))}}</h5>
                                <input placeholder="{{__(('pages.filter_tags'))}}" class="col-sm-2 col-2 form-control card-title" id="search-templates" type="text" />
                                <button class="col-sm-1 col-1 btn btn-save card-title js-partial-create" data-url="{{route('templates.create')}}" data-script="{{ asset('js/template.js') }}"><i class="fa fa-plus"></i></button>
                                <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                            </div>
                            <div class="w-100">
                                @isset($templates)
                                <table class="table table-responsive-md js-partial-table" id="templates-table" data-url="{{route('material.templates.get')}}">
                                    <thead>
                                        <tr>
                                            <th>{{__(('pages.name'))}}</th>
                                            <th>Tags</th>
                                            <th>{{__(('pages.available'))}}</th>
                                            <th>{{__(('pages.latest_alteration'))}}</th>
                                            <th>{{__(('pages.editor'))}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="material-templates-tbody">
                                        @foreach($templates as $template)
                                        <tr data-url="{{route('templates.edit', ['template' => $template->id])}}" data-script="{{ asset('js/template.js') }}">
                                            <td>{{$template->name}}</td>
                                            <td>{{$template->text_tags}}</td>
                                            <td>@if($template->is_available)<i class="fa fa-check"></i>@else<i class="fa fa-times"></i>@endif</td>
                                            <td>{{$template->updated_at}}</td>
                                            @if($template->editedBy)
                                            <td>{{$template->editedBy->name}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <div class="row">
                                <h5 class="col-sm-11 col-10 card-title">Categorieën</h5>
                                <button class="col-sm-1 col-1 btn btn-save js-partial-create" data-url="{{route('category.create')}}" data-script="{{ asset('js/category.js') }}"><i class="fa fa-plus"></i></button>
                                <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                            </div>
                            <div class="w-100">
                                <table class="table js-partial-table" data-url="{{route('material.categories.get')}}" id="categories-table">
                                    <thead>
                                    <tr>
                                        <th>Naam</th>
                                    </tr>
                                    </thead>
                                    @isset($categories)
                                        <tbody>
                                        @foreach($categories as $category)
                                            <tr data-url="{{route('category.edit', ['category' => $category->id])}}" data-script="{{ asset('js/category.js') }}">
                                                <td>{{$category->name}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    @endisset
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="crud-pane" role="tabpanel">
                    <div class="card card-shadow">
                        <div class="card-body">
                            <div id="crud-body" style="margin-bottom: 2%">

                            </div>
                            <button type="button" class="btn btn-danger" id="crud-cancel-btn">
                                {{__('general.cancel')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#search-templates').on('keyup', function() {
            let value = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{route("material.search")}}',
                data: {
                    'search': value,
                    'type': 'normal'
                },

                success: function(data) {
                    $('#material-templates-tbody').html(data);
                }
            });
        });

        $('#search-collections').on('keyup', function() {
            let value = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{route("material.search")}}',
                data: {
                    'search': value,
                    'type': 'collection'
                },

                success: function(data) {
                    $('#material-collections-tbody').html(data);
                }
            });
        });
    });
</script>
@endsection