@extends('layouts.app')
@push('body')
    <div class="container">
        @formStart($form)
        @formWidget($form)
        <input type="submit" value="Opslaan"
               class="btn btn-primary"/>
        @formEnd($form)
    </div>
@endpush
