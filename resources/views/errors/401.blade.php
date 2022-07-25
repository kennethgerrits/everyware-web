@extends('layouts.app')
@push('body')
<div class="alert alert-danger" role="alert">
    Unauthorized error: {{ $exception->getMessage() }}
</div>
@endpush