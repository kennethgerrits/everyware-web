<?php
if (!function_exists('getAverage')) {
    function getAverage($worksheets)
    {
        $questionAmount = 0;
        $successAmount = 0;
        foreach ($worksheets as $worksheet){
            $questionAmount += $worksheet->question_amount;
            $successAmount += $worksheet->success_amount;
        }


        if($successAmount == 0 || $questionAmount == 0){
            return 0;
        }
        return round($successAmount/$questionAmount * 100, 2);
    }
}

if (!function_exists('getSuccessRate')) {
    function getSuccessRate($worksheets)
    {
        $succeeded = 0;
        foreach ($worksheets as $worksheet){
            if($worksheet->cesuur <= $worksheet->success_amount){
                $succeeded++;
            }
        }

        if($worksheets->count() == 0 || $succeeded == 0){
            return 0;
        }

        return round($succeeded/$worksheets->count() * 100, 2);
    }
}

if (!function_exists('getTimeSpend')) {
    function getTimeSpend($worksheets)
    {
        $totalTimeSpend = 0;
        foreach ($worksheets as $worksheet){
            $started_at = \Carbon\Carbon::parse($worksheet->started_at);
            $ended_at = \Carbon\Carbon::parse($worksheet->ended_at);

            $totalTimeSpend += $started_at->diffInSeconds($ended_at);
        }

        if($totalTimeSpend == 0 || $worksheets->count() == 0){
            return 0;
        }

        return round($totalTimeSpend/$worksheets->count(), 2);
    }
}
