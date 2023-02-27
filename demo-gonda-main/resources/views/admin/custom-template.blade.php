{{--<table>--}}
{{--    <tr>--}}
{{--        <th style="width:20%">S.No</th>--}}
{{--        <th style="width:40%">Question</th>--}}
{{--        <th style="width:40%">Answer</th>--}}
{{--    </tr>--}}
{{--    @forelse($question_and_answer as $key => $question_and_answer_data)--}}
{{--    <tr>--}}
{{--        <td style="width:20%">{{++$key}}</td>--}}
{{--        <td style="width:40%">{{$question_and_answer_data['question']}}</td>--}}
{{--        <td style="width:40%">{{$question_and_answer_data['answer']}}</td>--}}
{{--    </tr>--}}
{{--    @empty--}}
{{--    @endforelse--}}

{{--</table>--}}

@forelse($question_and_answer as $key => $question_and_answer_data)

        <p><strong>{{$question_and_answer_data['question']}}</strong></p>
        <p>{{$question_and_answer_data['answer']}}</p>

@empty
@endforelse


