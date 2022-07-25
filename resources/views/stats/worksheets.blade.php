@extends('layouts.app')
@push('body')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="row">
                        <h5 class="col-12 card-title">
                            <a href="{{route('stats.index')}}">{{__('stats.stats_worksheets')}}</a>
                            >
                            <a href="{{route('stats.users', ['template_id' => $template->id])}}"> {{$template->name}}</a>
                            > {{$user->name}}
                        </h5>
                    </div>
                    <div class="w-100">
                        <table class="table table-responsive-md js-crud-table">
                            <thead>
                                <tr>
                                    <th>{{__('stats.start_time')}}</th>
                                    <th>{{__('stats.end_time')}}</th>
                                    <th>Cesuur</th>
                                    <th>{{__('stats.correct_total_questions')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($worksheets->count())
                                @foreach($worksheets as $worksheet)
                                <tr class="js-worksheet-row" data-id="{{$worksheet->id}}" data-url="#">
                                    <td>{{date('j F, Y, H:i:s', strtotime($worksheet->started_at))}}</td>
                                    <td>{{date('H:i:s', strtotime($worksheet->ended_at))}}</td>
                                    <td>{{$worksheet->cesuur}}</td>
                                    <td>{{$worksheet->success_amount}} / {{$worksheet->question_amount}}</td>
                                    @if($worksheet->cesuur <= $worksheet->success_amount)
                                        <td>
                                            <i class="fa fa-check red" style="color:forestgreen"></i>
                                        </td>
                                        @else
                                        <td>
                                            <i class="fa fa-times" style="color: red"></i>
                                        </td>
                                        @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8" id="questions">
            {{-- @include('stats.partial.worksheet')--}}
        </div>
    </div>
</div>
@endpush
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-worksheet-row').on('click', function() {
            let id = $(this).data('id');
            $.ajax({
                url: "{{route('stats.worksheet.details', ['template_id' => $template->id, 'user_id' => $user->id, 'worksheet_id' => '#1'])}}".replace("#1", id),
                method: "get",
                success: function(data) {
                    $('#questions').empty();
                    $('#questions').append(data);
                }
            });
        })
    });
</script>
@endsection
