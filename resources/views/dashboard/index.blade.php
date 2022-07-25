@extends('layouts.app')
@push('body')
<div class="container mt-0">
    <div class="">
        <h2>{{__('dashboard.greeting', ['Name' => $logged_in_user->first_name, 'time' => $day_term])}}!
        </h2>
        <p>{{__('dashboard.collected_info')}}</p>
    </div>

</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <h4>
                @if ($logged_in_user->isAdmin())
                {{__('dashboard.recent_activities_classes_all')}}
                @else
                {{__('dashboard.recent_activities_classes_your')}}
                @endif
            </h4>

            <ul class="list-group mb-4">
                @foreach($texts as $text)
                <li class="list-group-item">
                    {{$text}}
                </li>
                @endforeach
            </ul>

            <h4> {{__('dashboard.recent_activities_worksheets')}} </h4>
            <ul class="list-group mb-4">
                @foreach($recently_edited_worksheets as $worksheet)
                <li class="list-group-item">
                    <i class="fas fa-edit"></i> {{$worksheet->name}} is
                    @if ($worksheet->created_at == $worksheet->updated_at)
                    {{__('general.created')}}.
                    @else
                    {{__('general.altered')}}.
                    @endif <br>
                    <i class="fas fa-clock"></i> {{$worksheet->updated_at->format('d-m-Y H:i:s')}} <br>
                    <i class="fas fa-user"></i> {{$worksheet->editedBy->name ?? ""}} <br>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-4 col-sm-12">

            <h4>
                @if ($logged_in_user->isAdmin())
                {{__('dashboard.all_collegues')}}
                @else
                {{__('dashboard.your_collegues')}}
                @endif
            </h4>
            <ul class="list-group mb-4">
                @foreach($teachers as $teacher)
                <li class="list-group-item">{{$teacher->name}}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endpush
