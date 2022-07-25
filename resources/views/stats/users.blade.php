@extends('layouts.app')
@push('body')
<div class="container">
    <div style="z-index:100; position: fixed;" class="succes-refresh">

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="row">
                        <h5 class="col-sm-11 col-10 card-title">
                            <a href="{{route('stats.index')}}"> {{__('stats.stats_worksheets')}}</a>
                            > {{$template->name}}
                            @if($users->count())
                            @php
                            $statistic_refresh = $users[0]->statistics()->where('template_id', $template->id)->first();
                            @endphp
                            @if($statistic_refresh != null)
                            - {{__('stats.latest_refresh')}}: {{$statistic_refresh->latest_refresh}} 
                            @endif
                            @endif
                        </h5>
                        <button class="col-sm-1 col-1 btn btn-save" id="refresh-button"><i class="fas fa-sync"></i>
                        </button>
                        <div class="col-1 d-sm-none d-md-none d-lg-none d-xl-none"></div>
                    </div>
                    <div class="w-100">
                        <table class="table table-responsive-md js-crud-table">
                            <thead>
                                <tr>
                                    <th>{{__('stats.name')}}</th>
                                    <th>{{__('stats.avg_questions_correct')}}</th>
                                    <th>{{__('stats.num_attempts')}}</th>
                                    <th>{{__('stats.avg_time')}}</th>
                                    <th>{{__('stats.success_rate')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->count())
                                @foreach($users as $user)
                                @php
                                $statistic = $user->statistics()->where('template_id', $template->id)->first();
                                @endphp
                                <tr data-url="{{route('stats.worksheets', ['template_id' => $template->id, 'user_id' => $user->id])}}">
                                    <td>{{$user->name}}</td>
                                    <td>{{$statistic->average}}%</td>
                                    <td>{{$statistic->attempts}}</td>
                                    <td>{{Carbon\CarbonInterval::seconds($statistic->average_time_spend)->cascade()->forHumans()}}</td>
                                    <td>{{$statistic->success_rate}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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

        $('#refresh-button').on('click', function() {
            $(".succes-refresh").html('<img src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif"/>');
            $.ajax({
                url: "{{route('stats.refresh')}}",
                method: "GET",
                async: true,
                success: function() {
                    $(".succes-refresh").html("");
                }
            })
        })

    });
</script>
@endsection