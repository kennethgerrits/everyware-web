@extends('layouts.app')
@push('body')

<div class="container">
    <div class="materials-bg"></div>
    <div class="row">
        <div class="col-lg-4 mb-2">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="flex jc-sb">
                        <h5 class="card-title">{{__(('pages.groups'))}}</h5>
                        <a class="btn btn-save vertical-center" href="{{route('class-groups.create')}}"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                    <table class="table js-crud-table">
                        <thead>
                            <tr>
                                <th>{{__(('pages.name'))}}</th>
                                <th>{{__(('pages.teachers'))}}</th>
                                <th>{{__(('pages.num_students'))}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classgroups as $classgroup)
                            <tr data-url="{{route('class-groups.edit', ['class_group' => $classgroup->id])}}">
                                <td>{{$classgroup->name}}</td>
                                <td>
                                    @isset($classgroup->teachers)
                                    @foreach ($classgroup->teachers as $teacher)
                                    {{ $teacher->name }}@if (!$loop->last), @endif
                                    @endforeach
                                    @endisset
                                </td>
                                <td>{{$classgroup->students->count()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="row">
                        <h5 class="col-sm-11 col-10 card-title">{{__(('pages.users'))}}</h5>
                        @can('manage-users')
                        <a class="col-sm-1 col-1 btn btn-save" href="{{route('users.create')}}"><i class="fa fa-plus"></i></a>
                        @endcan
                        <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                    </div>
                    <div class="w-100">
                        @isset($users)
                        <table class="table table-responsive-md js-crud-table">
                            <thead>
                                <tr>
                                    <th>{{__(('pages.group'))}}</th>
                                    <th>{{__(('pages.name'))}}</th>
                                    <th>{{__(('pages.role'))}}</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-url="{{route('users.edit', ['user' => $user->id])}}">
                                    <td>
                                        @if($user->studentGroups)
                                        @foreach ($user->studentGroups as $group)
                                        {{ $group->name }}@if (!$loop->last), @endif
                                        @endforeach
                                        @endif
                                    </td>

                                    <td>{{$user->name}}</td>
                                    <td>{{$user->plain_roles}}</td>
                                    <td>{{$user->email}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush