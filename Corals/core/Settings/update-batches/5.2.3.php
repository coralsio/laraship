<?php

use Corals\Settings\Models\Setting;
use Illuminate\Support\Str;

Setting::query()
    ->where('type', 'SELECT')
    ->each(function ($setting) {
        $value = $setting->value;

        if ($value && !is_array($value)) {
            $value = str_replace('\\', '', $value);
            $value = Str::startsWith($value, '"') ? Str::replaceFirst('"', '', $value) : $value;
            $value = Str::endsWith($value, '"') ? Str::replaceLast('"', '', $value) : $value;

            $setting->value = $value;
            $setting->save();
        }
    });
