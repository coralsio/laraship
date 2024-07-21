<?php

namespace Corals\Utility\Classes;

use Illuminate\Support\Arr;

class Utility
{
    protected $utilityModules = [];

    public function addToUtilityModules($module)
    {
        array_push($this->utilityModules, $module);
    }

    public function getUtilityModules()
    {
        return array_combine($this->utilityModules, $this->utilityModules);
    }

    public function getPredefinedDates()
    {
        foreach (config('utility.pre_defined_date.options', []) as $key => $attr) {
            $from = data_get($attr, 'from');
            $to = data_get($attr, 'to');

            if (data_get($attr, 'is_eval')) {
                $startDate = eval($from);
                $endDate = eval($to);
            } else {
                $startDateCarbonMethod = key($from);
                $startDateCarbonValue = $from[$startDateCarbonMethod];
                $endDateCarbonMethod = key($to);
                $endDateCarbonValue = $to[$endDateCarbonMethod];

                $startDate = now()->{$startDateCarbonMethod}($startDateCarbonValue)->toDateString();
                $endDate = now()->{$endDateCarbonMethod}($endDateCarbonValue)->toDateString();
            }


            $result[$key] = [
                'label' => data_get($attr, 'label'),
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
        }

        return $result ?? [];
    }

    public function gerPredefinedDatesOptions($monthly = false)
    {
        $excludedOptionsIfMonthly = [
            'year_to_today',
            'month_to_today',
            'previous_quarter',
            'current_quarter',
            'next_quarter',
            'yesterday',
            'today',
            'tomorrow',
            'last_week',
            'current_week',
        ];

        $predefinedDates = $this->getPredefinedDates();
        $options = array_combine(array_keys($predefinedDates), Arr::pluck($predefinedDates, 'label'));

        if ($monthly) {
            $options = array_diff_key($options, array_flip($excludedOptionsIfMonthly));
        }

        return $options;
    }

}
