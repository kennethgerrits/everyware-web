<div class="card card-shadow">
    <div class="card-body">
        <div class="row">
            <h5 class="col-12 card-title">{{__('stats.questions')}}</h5>
        </div>
        <div class="w-100">
            <table class="table table-responsive-md">
                <thead>
                    <tr>
                        <th>{{__('stats.correct_answer')}}</th>
                        @if($worksheet->question_type == "LISTENING" || $worksheet->question_type == "ARITHMETIC_LISTENING")
                        <th>{{__('stats.audio_correct_answer')}}</th>
                        @elseif($worksheet->question_type == "STATIC_IMAGE")
                        <th>{{__('stats.picture_correct_answer')}}</th>
                        @endif
                        <th>{{__('stats.answer')}}</th>

                        @if($worksheet->answer_type == "MULTIPLE_CHOICE")
                        <th>{{__('stats.poss_answers')}}</th>

                        @elseif($worksheet->answer_type == "WRITING" || $worksheet->answer_type == "DRAG")
                        <th>{{__('stats.picture')}}</th>

                        @elseif($worksheet->answer_type == "VOICE")
                        <th>{{__('stats.audio_clip')}}</th>

                        @endif

                        <th>{{__('stats.duration')}}</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($worksheet->questions as $question)
                    <tr>
                        @if($worksheet->question_type == "LISTENING")
                        <td> {{$question['correct_answer']['name']}}</td>
                        <td>
                            <audio controls="controls">
                                <source src="data:audio/wav;base64, {{$question['audio_question']}}" />
                            </audio>
                        </td>
                        @elseif($worksheet->question_type == "STATIC_IMAGE")
                        <td> {{$question['correct_answer']['name']}}</td>
                        <td>
                            <img src="data:image/jpeg;base64,{{$question['image_question']}}" style="max-width: 100px; height: auto" />
                        </td>
                        @elseif($worksheet->question_type == "ARITHMETIC_IMAGE")
                        <td> {{$question['correct_answer']['amount']}}</td>
                        @elseif($worksheet->question_type == "STATIC_TEXT")
                        <td> {{$question['correct_answer']['name']}}</td>
                        @elseif($worksheet->question_type == "ARITHMETIC_SUM_TEXT")
                        <td> {{$question['correct_answer']['amount']}}</td>
                        @endif

                        {{-- Answers--}}
                        @if($worksheet->answer_type == "MULTIPLE_CHOICE")
                        <td> {{$question['selected_answer']['name']}}</td>

                        <td> {{ implode(" | ", $question['possible_answers'])}}</td>
                        @elseif($worksheet->answer_type == "DRAG")
                        <td> {{$question['selected_answer']['amount']}}</td>
                        <td>
                            <img src="data:image/jpeg;base64,{{$question['image']['url']}}" style="max-width: 300px; height: auto" />
                        </td>

                        @elseif($worksheet->answer_type == "WRITING")
                        <td> {{$question['selected_answer']['name']}}</td>
                        <td>
                            <img src="data:image/jpeg;base64,{{$question['image']['url']}}" style="max-width: 300px; height: auto" />
                        </td>

                        @elseif($worksheet->answer_type == "VOICE")
                        <td> {{$question['selected_answer']['name']}}</td>
                        <td>
                            <audio controls="controls">
                                <source src="data:audio/wav;base64, {{$question['audio_input']}}" />
                            </audio>
                        </td>

                        @endif

                        <td> {{$question['duration'] / 1000}} seconden</td>

                        @if($question['success'])
                            <td>
                                <i class="fa fa-check" style="color: green"></i>
                            </td>
                            @else
                            <td>
                                <i class="fa fa-times" style="color: red"></i>
                            </td>
                            @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
