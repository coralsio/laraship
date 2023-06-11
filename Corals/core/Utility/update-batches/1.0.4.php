<?php


\Corals\Settings\Models\Setting::where('code', ['schedule_time'])
    ->update(['code' => 'utility_schedule_time', 'category' => 'Utilities']);

\Corals\Settings\Models\Setting::where('code', ['days_of_the_week'])
    ->update(['code' => 'utility_days_of_the_week', 'category' => 'Utilities']);