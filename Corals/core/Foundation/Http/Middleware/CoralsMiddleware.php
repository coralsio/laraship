<?php


namespace Corals\Foundation\Http\Middleware;


use Corals\Foundation\View\Facades\JavaScriptFacade;
use Corals\Settings\Facades\Settings;

class CoralsMiddleware
{

    public function handle($request, \Closure $next)
    {
        $this->setSharedJS();

        return $next($request);
    }

    protected function setSharedJS()
    {
        JavaScriptFacade::put([
            'filter_open' => trans('Corals::labels.filter_open'),
            'filter_close' => trans('Corals::labels.filter_close'),
            'confirmation' => trans('Corals::labels.confirmation'),
            'dateInputFormat' => Settings::get('date_input_format', 'YYYY-MM-DD'),
            'defaultSelectedHour' => Settings::get('default_selected_hour', '08:00:00')
        ]);
    }
}
