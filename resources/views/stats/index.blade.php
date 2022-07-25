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
                        <h5 class="col-sm-11 col-10 card-title">{{__('stats.stats_worksheets')}}

                            @if($templates->count())
                            - {{__('stats.latest_refresh')}}: {{$templates[0]->statistic->latest_refresh}}
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
                                    <th>{{__('stats.success_rate')}}</th>
                                    <th>{{__('stats.avg_time')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="template-body">
                                @if($templates->count())
                                @foreach($templates as $template)
                                <tr data-url="{{route('stats.users', ['template_id' => $template->id])}}">
                                    <td>{{$template->name}}</td>
                                    <td>{{$template->statistic->average}}%</td>
                                    <td>{{$template->statistic->success_rate}}%</td>
                                    <td>{{Carbon\CarbonInterval::seconds($template->statistic->average_time_spend)->cascade()->forHumans()}}</td>
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
            $('#refresh-button').prop('disabled', true);
            $(".succes-refresh").html('<img src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif"/>');
            $.ajax({
                url: "{{route('stats.refresh')}}",
                method: "GET",
                async: true,
                success: function() {
                    $(".succes-refresh").html("");
                    $('#refresh-button').prop('disabled', false);
                    location.reload();
                }
            })
        })

    });
</script>
@endsection
