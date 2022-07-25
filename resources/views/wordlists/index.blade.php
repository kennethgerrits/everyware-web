@extends('layouts.app')
@push('body')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-shadow ">
                <div class="card-body">
                    <div class="row">
                        <h5 class="col-sm-11 col-10 card-title">{{__('pages.wordlists')}}</h5>
                        <a class="col-sm-1 col-1 btn btn-save" href="{{route('wordlists.create')}}"><i class="fa fa-plus"></i></a>
                        <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                    </div>
                    <div class="w-100">
                        @isset($wordLists)
                        <table class="table table-responsive-md js-crud-table">
                            <thead>
                                <tr>
                                    <th>{{__('pages.name')}}</th>
                                    <th>Type</th>
                                    <th>{{__('pages.latest_alteration')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wordLists as $wordList)
                                <tr data-url="{{route('wordlists.edit', ['wordlist' => $wordList->id])}}">
                                    <td>{{$wordList->name}}</td>
                                    <td>{{$wordList->type_text}}</td>
                                    <td><i class="far fa-calendar"></i> {{$wordList->updated_at->format('d-m-Y')}} <i class="far fa-clock"></i> {{$wordList->updated_at->format('H:i')}}</td>
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