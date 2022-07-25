@extends('layouts.app')
@push('body')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="row">
                        <h5 class="col-sm-11 col-10 card-title">{{__(('pages.users'))}}</h5>
                        <a class="col-sm-1 col-1 btn btn-save" href="{{route('users.create')}}"><i class="fa fa-plus"></i></a>
                        <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                    </div>
                    <div class="w-100">
                        @isset($users)
                        <table class="table table-responsive-md js-crud-table">
                            <thead>
                                <tr>
                                    <th>{{__(('pages.name'))}}</th>
                                    <th>{{__(('pages.role'))}}</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-url="{{route('users.edit', ['user' => $user->id])}}">
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