<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

if (!function_exists('is_api_request')) {
    function is_api_request()
    {
        if (request()->route()) {
            $computedMiddleware = request()->route()->computedMiddleware;
        } else {
            $computedMiddleware = [];
        }

        if (in_array('api', $computedMiddleware)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('is_demo_mode')) {
    /**
     * @return mixed
     */
    function is_demo_mode()
    {
        return config('app.demo_mode');
    }
}

if (!function_exists('throw_demo_exception')) {
    /**
     * @throws Exception
     */
    function throw_demo_exception()
    {
        throw new \Exception('this action is blocked in demo mode for security purposes');
    }
}

if (!function_exists('array_hashids_encode')) {
    function array_hashids_encode($array, $idKey = 'id')
    {
        return array_map(function ($element) use ($idKey) {
            $element[$idKey] = hashids_encode($element[$idKey]);

            return $element;
        }, $array);
    }
}

if (!function_exists('hashids_encode')) {
    /**
     * Encode the given id.
     * @param $id
     * @param bool $force
     * @return mixed
     */
    function hashids_encode($id, bool $force = false)
    {
        if (!$force && is_api_request()) {
            return $id;
        }

        return \Corals\Foundation\Facades\Hashids::encode($id);
    }
}

if (!function_exists('hashids_decode')) {
    /**
     * Decode the given value.
     * @param $value
     * @return null
     */
    function hashids_decode($value)
    {
        if (is_api_request()) {
            return $value;
        }

        if (!$value) {
            return null;
        }

        $decoded_value = \Corals\Foundation\Facades\Hashids::decode($value);

        if (empty($decoded_value)) {
            return null;
        }

        if (count($decoded_value) == 1) {
            return $decoded_value[0];
        }

        return $decoded_value;
    }
}

if (!function_exists('removeEmptyArrayElement')) {
    function removeEmptyArrayElement($attribute)
    {
        // check for empty strings and null values
        // 0 excluded for cases such as min=0 in input attributes

        if ($attribute === 0 || $attribute === false) {
            return true;
        }

        return !empty($attribute);
    }
}

if (!function_exists('format_date')) {
    /**
     * @param $date
     * @param string $format
     * @return false|null|string
     */
    function format_date($date, $format = 'd M, Y')
    {
        if (empty($date)) {
            return null;
        }

        return date($format, strtotime($date));
    }
}

if (!function_exists('format_date_time')) {
    /**
     * @param $datetime
     * @param string $format
     * @return false|string
     */
    function format_date_time($datetime, $format = 'd M, Y h:i A')
    {
        if (empty($datetime)) {
            return null;
        }

        return date($format, strtotime($datetime));
    }
}

if (!function_exists('format_time')) {
    /**
     * @param $time
     * @param string $format
     * @return false|string
     */
    function format_time($time, $format = 'h:i A')
    {
        return date($format, strtotime($time));
    }
}

if (!function_exists('log_exception')) {
    function log_exception(
        \Exception $exception = null,
        $object = null,
        $action = null,
        $message = null,
        $echo_message = false
    )
    {
        logger(array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2), -1));

        if ($exception) {
            report($exception);
            $message = $exception->getMessage() . '. ' . ($message ?? '');
        }

        $activity = activity()
            ->inLog('exception')
            ->withProperties(['attributes' => ['action' => $action, 'object' => $object, 'message' => $message]]);

        if (user()) {
            $activity = $activity->causedBy(user());
        }

        $activity = $activity->log(\Str::limit($message, 180));

        if (request()->ajax()) {
            $message = ['level' => 'error', 'message' => $message];

            request()->session()->flash('notification', $message);

            if ($echo_message) {
                $return_message = ['notification' => $message];
                echo json_encode($return_message);
                die();
            } elseif (request()->wantsJson()) {
                /**   TODO::restructure the exception log **/
//                    if ($echo_message) {
//                $return_message = ['notification' => $message];
//                echo response()->json($return_message);
//                    }
            } else {
                $return_message = ['notification' => $message];
                echo json_encode($return_message);
                die();
            }
        } else {
            flash($message, 'error');
        }
    }
}

if (!function_exists('generatePopover')) {
    function generatePopover($content, $text = '', $icon = 'fa fa-sticky-note', $placement = 'bottom', $trigger = null)
    {
        if (empty($content)) {
            return '-';
        }

        if (is_array($content)) {
            $content = json_encode($content);
        }

        $content = iconv(mb_detect_encoding($content, mb_detect_order(), true), "UTF-8", $content);
//        $content = addslashes($content);
        $content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

        return '<a href="#" onclick="event.preventDefault();" data-toggle="popover" data-placement="' . $placement . '" data-html="true" ' . (!is_null($trigger) ? ('data-trigger="' . $trigger . '"') : '') . '" data-content="' . $content . '"><i class="' . $icon . '"></i> ' . $text . '</a>';
    }
}

if (!function_exists('formatStatusAsLabels')) {
    function formatStatusAsLabels($status, $customConfig = [])
    {
        $is_active = $status == 'active' || $status === 1 || $status === true;

        $is_inactive = $status == 'inactive' || $status === 0 || $status === false;

        $is_pending = $status == 'pending' || $status === 0 || $status === false;

        $default_translation_key = (is_numeric($status) || is_bool($status)) ? 'Corals::attributes.status_options_boolean.' : 'Corals::attributes.status_options.';

        $defaultLevel = $is_active ? 'success' : ($is_inactive ? 'warning' : ($is_pending ? 'info' : 'default'));

        $level = \Arr::get($customConfig, 'level', $defaultLevel);

        $icon = \Arr::get($customConfig, 'icon', '');

        if (\Illuminate\Support\Facades\Lang::has($default_translation_key . ($status ?: 0))) {
            $defaultText = trans($default_translation_key . ($status ?: 0));
        } else {
            $defaultText = ucfirst($status);
        }

        $text = trans(\Arr::get($customConfig, 'text', $defaultText));

        if ($level == 'default') {
            $badgeLevel = 'secondary';
        } else {
            $badgeLevel = $level;
        }

        $response = "<span class=\"badge label label-{$level} badge-{$badgeLevel} \">{$icon} {$text}</span>";

        return $response;
    }
}
if (!function_exists('formatArrayAsLabels')) {
    function formatArrayAsLabels($array, $level = 'default', $icon = '', $show_key = false)
    {
        $response = '';

        if (!$array) {
            return '';
        }

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $item = json_encode($item);
            }
            if ($show_key) {
                $response .= "<span class=\"label label-{$level} badge badge-{$level} m-r-5 mr-1 m-b-5 mb-1 \">{$icon} {$key} : <b> {$item} </b></span>";
            } else {
                $response .= "<span class=\"label label-{$level} badge badge-{$level} m-r-5 mr-1 m-b-5 mb-1\">{$icon} {$item}</span>";
            }
        }

        if (empty($response)) {
            return '-';
        }

        return $response;
    }
}

if (!function_exists('getGatewayStatus')) {
    function getGatewayStatus($item)
    {
        return $item->gateway_status ? ($item->gateway_status == 'failed' ? generatePopover($item->gateway_message,
            ucfirst($item->gateway_status),
            'fa fa-times-circle-o text-danger') : '<i class="fa fa-check-circle-o text-success"></i> ' . ucfirst($item->gateway_status)) : 'NA';
    }
}

if (!function_exists('maxUploadFileSize')) {
    function maxUploadFileSize($unit = 'KB')
    {
        $size = config('media-library.max_file_size');

        switch ($unit) {
            case 'B':
                break;
            case 'KB':
                $size = $size / 1024;
                break;
            case 'MB':
                $size = $size / (1024 * 1024);
                break;
        }

        return $size;
    }
}

if (!function_exists('redirectTo')) {
    /**
     * @param null $to
     * @param int $status
     * @param array $headers
     * @param null $secure
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    function redirectTo($to = null, $status = 302, $headers = [], $secure = null)
    {
        $request = request();
        if ($request->wantsJson()) {
            $result = ['status' => 'success', 'action' => 'redirectTo', 'url' => url($to)];

            if ($request->has('translation_submit')) {
                unset($result['action']);
            }

            if ($request->session()->has('notification')) {
                $result['notification'] = $request->session()->pull('notification');
            }

            $request->session()->reflash();

            return response()->json($result);
        }
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('getKeyValuePairs')) {
    /**
     * @param $pairs
     * @return array
     */
    function getKeyValuePairs($pairs)
    {
        if (empty($pairs)) {
            return [];
        }

        if (!is_array($pairs)) {
            $pairs = json_decode($pairs, true) ?? [];
        }

        $response = [];
        foreach ($pairs as $pair) {
            $response[current($pair)] = next($pair);
        }

        return $response;
    }
}

if (!function_exists('getQueryWithParameters')) {
    function getQueryWithParameters($query)
    {
        $addSlashes = str_replace('?', "'?'", $query->toSql());

        $sql = str_replace('%', '#', $addSlashes);

        $sql = str_replace('?', '%s', $sql);

        $sql = vsprintf($sql, $query->getBindings());

        $sql = str_replace('#', '%', $sql);

        if (true) {
            logger('xxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
            logger($sql);
            logger('yyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
        }

        return $sql;
    }
}

if (!function_exists('cleanSpecialCharacters')) {
    function cleanSpecialCharacters($string)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string);

        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        // Replaces multiple hyphens with single one.
        return preg_replace('/-+/', '-', $string);
    }
}

if (!function_exists('generateCopyToClipBoard')) {
    function generateCopyToClipBoard($key, $text, $displayText = '')
    {
        $selector = 'shortcode_' . cleanSpecialCharacters($key);

        if (!empty($displayText)) {
            return '<a href="#" onclick="event.preventDefault();" class="copy-button" data-clipboard-text="' . $text . '">
                            ' . $displayText . ' <i class="fa fa-clipboard"></i></a>';
        }

        return '<b id="' . $selector . '" >' . $text . '</b> 
                <a href="#" onclick="event.preventDefault();" class="copy-button"
                data-clipboard-target="#' . $selector . '"><i class="fa fa-clipboard"></i></a>';
    }
}

if (!function_exists('schemaHasTable')) {
    function schemaHasTable($table)
    {
        return \Cache::remember('schema_has_' . $table, config('corals.cache_ttl'), function () use ($table) {
            try {
                return \Schema::hasTable($table);
            } catch (\Exception $exception) {
                return false;
            }
        });
    }
}

if (!function_exists('getColsInRows')) {
    function getColsInRows($fieldClass)
    {
        switch ($fieldClass) {
            case 'col-md-1':
                $fieldsInRow = 12;
                break;
            case 'col-md-2':
                $fieldsInRow = 6;
                break;
            case 'col-md-3':
                $fieldsInRow = 4;
                break;
            case 'col-md-4':
                $fieldsInRow = 3;
                break;
            case 'col-md-5':
            case 'col-md-6':
                $fieldsInRow = 2;
                break;
            case 'col-md-7':
            case 'col-md-8':
            case 'col-md-9':
            case 'col-md-10':
            case 'col-md-11':
            case 'col-md-12':
                $fieldsInRow = 1;
                break;
            default:
                $fieldsInRow = 3;
        }

        return $fieldsInRow;
    }
}

if (!function_exists('renderContentInBSRows')) {
    function renderContentInBSRows($content, $colClass = 'col-md-12')
    {
        $j = 0;

        $colsInRow = getColsInRows($colClass);

        $output = '';

        if (!is_array($content)) {
            $content = [$content];
        }

        foreach ($content as $columnContent) {
            if ($j == 0) {
                $output .= '<div class="row">';
            }

            $output .= '<div class="' . $colClass . '">';

            $output .= $columnContent;

            $output .= '</div>';

            if (++$j == $colsInRow) {
                $output .= '</div>';
                $j = 0;
            }
        }

        if ($j > 0) {
            $output .= '</div>';
        }

        return $output;
    }
}

if (!function_exists('get_key_translation')) {
    function get_key_translation($key)
    {
        return trans($key);
    }
}

if (!function_exists('get_array_key_translation')) {
    function get_array_key_translation($array)
    {
        return array_map('get_key_translation', $array);
    }
}

if (!function_exists('cleanJSONFileContent')) {
    function cleanJSONFileContent($content)
    {
        // remove comments
        $content = preg_replace('!/\*.*?\*/!s', '', $content);

        // remove empty lines that can create errors
        $content = preg_replace('/\n\s*\n/', "\n", $content);

        return $content;
    }
}

if (!function_exists('urlWithParameters')) {
    function urlWithParameters($urlString, $params = [])
    {
        if (!$urlString) {
            return '';
        }

        $url = url($urlString);

        if (!empty($params)) {
            $url = $url . '?' . http_build_query($params);
        }

        return $url;
    }
}

if (!function_exists('getObjectClassForViews')) {
    function getObjectClassForViews($object)
    {
        return str_replace('\\', '\\\\', get_class($object));
    }
}


if (!function_exists('checkActiveKey')) {
    function checkActiveKey($value, $compareWithKey)
    {
        if (request()->has($compareWithKey)) {
            $compareWithKey = request()->get($compareWithKey);

            if (is_array($compareWithKey)) {
                return array_search($value, $compareWithKey) !== false;
            } else {
                return $value == $compareWithKey;
            }
        }
    }
}
if (!function_exists('HtmlElement')) {
    function HtmlElement(string $tag, $attributes = null, $content = null): string
    {
        return \Spatie\HtmlElement\HtmlElement::render(...func_get_args());
    }
}

if (!function_exists('getUserByHash')) {
    function getUserByHash($user_hashed_id)
    {
        if (!$user_hashed_id) {
            return null;
        }

        return Corals\User\Models\User::findByHash($user_hashed_id);
    }
}

if (!function_exists('isJoined')) {
    function isJoined($query, $table)
    {
        $joins = null;

        if ($query instanceof Illuminate\Database\Eloquent\Builder) {
            $joins = $query->getQuery()->joins;
        } else {
            if ($query instanceof Illuminate\Database\Query\Builder) {
                $joins = $query->joins;
            }
        }
        if ($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('randomCode')) {
    function randomCode($prefix = '', $length = 6)
    {
        //append dash if prefix exists
        $prefix .= !empty($prefix) ? '-' : '';

        return strtoupper($prefix . \Str::random($length));
    }
}

if (!function_exists('getMorphAlias')) {
    function getMorphAlias($modelClass)
    {
        if (!is_object($modelClass) && class_exists($modelClass)) {
            $model = new $modelClass;
        } elseif (is_object($modelClass)) {
            $model = $modelClass;
            $modelClass = get_class($modelClass);
        }

        if (isset($model) && method_exists($model, 'getMorphClass')) {
            return $model->getMorphClass();
        }

        return array_flip(Illuminate\Database\Eloquent\Relations\Relation::$morphMap)[$modelClass] ?? $modelClass;
    }
}

if (!function_exists('logSyncChanges')) {
    function logSyncChanges($changes, $parent, $modelClass, $properties = [])
    {
        $changesLogArray = [];

        if (is_object($modelClass)) {
            $modelClass = get_class($modelClass);
        }

        $subjectType = getMorphAlias($modelClass);

        if (!$subjectType) {
            throw new \Exception('logSyncChanges::invalid $subjectType|' . $modelClass . '|' . get_class($parent));
        }
        logger($changes);
        foreach ($changes as $type => $list) {
            foreach ($list as $id) {
                $changesLogArray[] = [
                    'log_name' => 'sync-changes',
                    'description' => $type,
                    'subject_id' => $id,
                    'subject_type' => $subjectType,
                    'causer_id' => user()->id,
                    'causer_type' => 'User',
                    'properties' => json_encode(array_merge([
                        'parent' => get_class($parent),
                        'parent_id' => $parent->id,
                    ], $properties)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (filled($changesLogArray)) {
            \DB::table('activity_log')->insert($changesLogArray);
        }
    }
}

if (!function_exists('floatValWithLeftMost')) {
    function floatValWithLeftMost($value)
    {
        return rescue(function () use ($value) {
            return floatval(preg_replace("/[^0-9.]/", "", $value));
        }, $value);
    }
}

if (!function_exists('formatProperties')) {
    function formatProperties($properties, $model = null)
    {
        try {
            $formattedResponse = '';

            if (!is_array($properties) && !empty($properties)) {
                $properties = $properties->toArray();
            }

            appendDetails($formattedResponse, $properties, $model);

            if (!empty($formattedResponse)) {
                $formattedResponse = '<table class="details-table">' . $formattedResponse . '</table>';
            }

            if (empty($formattedResponse)) {
                $formattedResponse = '';
            }

            return $formattedResponse;
        } catch (\Exception $exception) {
            log_exception($exception);
        } finally {
            return $formattedResponse;
        }
    }
}

if (!function_exists('appendDetails')) {
    function appendDetails(&$formattedResponse, $detailsArray, $model)
    {
        if (is_array($detailsArray)) {
            foreach ($detailsArray as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'])) {
                    continue;
                }
                if ($model && property_exists($model, 'activityLogViewExcludedKeys')) {
                    if (in_array($key, $model->activityLogViewExcludedKeys)) {
                        continue;
                    }
                }
                if ($model && method_exists($model, 'customLogFormatter')) {
                    [$key, $value] = $model->customLogFormatter($key, $value);

                    $formattedResponse .= "<tr><td>{$key}</td>";

                    $formattedResponse .= "<td><span style='word-break: break-all;'>{$value}</span></td></tr>";

                    continue;
                }

                $keyTitle = str_replace('_', ' ', Str::title($key));

                if (strlen($key) < 3) {
                    $keyTitle = strtoupper($keyTitle);
                }

                if (is_array($value)) {
                    $formattedResponse .= "<tr><td colspan='2'>{$keyTitle}</td></tr>";

                    appendDetails($formattedResponse, $value, $model);

                    $formattedResponse .= "<tr><td colspan='2' class='separator-tr'></td></tr>";
                } else {
                    if (empty($value)) {
                        $value = '-';
                    }

                    switch ($key) {
                        case 'confirmed_at':
                            $value = format_date_time($value);
                            break;
                        case 'status':
                            if ($model && method_exists($model, 'getFormattedStatusAttribute')) {
                                $value = $model->formattedStatus();
                            } else {
                                $value = formatStatusAsLabels($value);
                            }
                        default:
                            if (Str::endsWith($key, ['_id', '_code'])) {
                                //guess relation
                                $relationName = str_replace(['_id', '_code'], '', $key);
                                $relationName = lcfirst(str_replace('_', '', ucwords($relationName, '_')));
                                if ($model && method_exists($model, $relationName)) {
                                    $model->{$key} = $value;
                                    $model->load($relationName);
                                    if ($model->$relationName) {
                                        $value = $model->$relationName->getIdentifier();
                                        //for label in the activity show
                                        $key = $relationName;
                                    }
                                }
                            }
                    }

                    if (Str::contains($value, ['www', 'http'])) {
                        $value = HtmlElement('a', ['href' => $value, 'target' => '_blank'], $value);
                    } elseif ($value == strip_tags($value)) {
                        $value = ucwords($value);
                    }

                    $keyTitle = str_replace('_', ' ', Str::title($key));

                    $formattedResponse .= "<tr><td>{$keyTitle}</td>";

                    $formattedResponse .= "<td><span style='word-break: break-all;'>{$value}</span></td></tr>";
                }
            }
        }
    }
}

if (!function_exists('yesNoFormatter')) {
    /**
     * @param $value
     * @return string
     */
    function yesNoFormatter($value)
    {
        return $value ? 'Yes' : 'No';
    }
}

if (!function_exists('get_request_filters_array')) {
    function get_request_filters_array($requestFilters)
    {
        $array = explode("&", $requestFilters);

        $array = str_replace('#amp#', '&', $array);
        if (!(count($array) == 1 && $array[0] == "")) {
            $index = 0;

            foreach ($array as $key => $value) {
                $filter = explode("=", $value);
                preg_match_all('/(.*)\[(.*?)\]/', $filter[0], $matches);
                if (is_array($matches[0]) && (count($matches[0]) > 0)) {
                    if ($filter[1]) {
                        if (strpos($filter[1], ',') !== false) {
                            foreach (explode(',', $filter[1]) as $f) {
                                $array[$matches[1][0]][] = $f;
                            }
                        } else {
                            $nodeKey = empty(trim($matches[2][0], "'")) ? $index++ : trim($matches[2][0], "'");
                            $array[$matches[1][0]][$nodeKey] = $filter[1];
                        }
                    }
                } else {
                    if ($filter[1]) {
                        $array[$filter[0]] = $filter[1];
                    }
                }
                unset ($array[$key]);
            }
            return $array;
        } else {
            return [];
        }
    }
}

if (!function_exists('getModelMorphMap')) {
    function getModelMorphMap($model)
    {
        $modelClass = is_object($model) ? get_class($model) : $model;

        return array_flip(Illuminate\Database\Eloquent\Relations\Relation::$morphMap)[$modelClass] ?? $modelClass;
    }
}

if (!function_exists('getDefaultAdminTheme')) {
    function getDefaultAdminTheme()
    {
        $default_admin_theme = \Settings::get('active_admin_theme', config('themes.corals_admin'));

        if (session()->has('dashboard_theme')) {
            $default_admin_theme = session('dashboard_theme');
        }

        return $default_admin_theme;
    }
}

if (!function_exists('push_to_theme_notifications')) {
    /**
     * @param $message
     * @param $alert_class
     * @param $key
     */
    function push_to_general_site_notifications($message, $alert_class, $key): void
    {
        $generalSiteNotifications = session()->get('general_site_notifications', []);

        $generalSiteNotifications[$key] = [
            'message' => $message,
            'alert_class' => $alert_class
        ];

        session()->flash('general_site_notifications', $generalSiteNotifications);
    }
}

if (!function_exists('update_morph_columns')) {
    function update_morph_columns()
    {
        $blackListType = [
            'mime_type'
        ];

        $dbConnection = config('database.default');

        $schema = config("database.connections.$dbConnection.database");

        $tables = \DB::select("SELECT `table_name` as  TABLE_NAME  from INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$schema'");

        $morphColumns = [];

        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;

            foreach (\DB::select("show columns from `$tableName`") as $column) {
                if (\Str::is('*_type', $morphColumn = $column->Field) && !in_array($morphColumn, $blackListType)) {
                    $morphColumns[$tableName][] = $morphColumn;
                }
            }
        }


        foreach ($morphColumns as $table => $columns) {
            foreach ($columns as $column) {
                \DB::statement(preparer_update_morph_columns_query($table, $column));
            }
        }
    }
}

if (!function_exists('preparer_update_morph_columns_query')) {
    /**
     * @param $table
     * @param $column
     * @return string
     */
    function preparer_update_morph_columns_query($table, $column)
    {
        $morphsUpdateQuery = '';

        foreach (\Illuminate\Database\Eloquent\Relations\Relation::$morphMap as $alias => $modelClass) {
            $morphsUpdateQuery .= sprintf("WHEN %s = '%s' THEN '%s' ", $column, addslashes($modelClass), $alias);
        }

        return sprintf("update `%s` set %s = CASE %s Else %s END", $table, $column, $morphsUpdateQuery, $column);
    }
}

if (!function_exists('yesNoFormatter')) {
    /**
     * @param $value
     * @return string
     */
    function yesNoFormatter($value)
    {
        return $value ? 'Yes' : 'No';
    }
}

if (!function_exists('get_media_url')) {
    function get_media_url($media, $download = false, $useHashId = false)
    {
        $id = $media->id;

        if ($useHashId) {
            $id = hashids_encode($id);
        }

        $url = url('media/' . $id);

        if ($download) {
            $url .= '/download';
        }

        return $url;
    }
}

if (!function_exists('getMediaPublicURL')) {
    function getMediaPublicURL($media, $conversion = '', $expireAfter = 120)
    {
        if (!$media) {
            return '#';
        }

        $media->setCustomProperty('views', $media->getCustomProperty('views', 0) + 1);
        $media->save();

        $disk = $media->disk;

        if ($disk == 's3') {
            if (!Storage::disk($disk)->exists($media->getPath())) {
                return $media->getPath();
            }

            $url = $media->getTemporaryUrl(now()->addMinutes($expireAfter), $conversion);
        } else {
            $url = $media->getFullUrl($conversion);
        }

        return $url;
    }
}

if (!function_exists('get_models')) {
    /**
     * @param $config
     * @param bool $publicOnly
     * @return array
     */
    function get_models($config, $publicOnly = false)
    {
        $modelsConfig = config($config, []) ?? [];
        $models = [];

        foreach ($modelsConfig as $config) {
            if ($publicOnly && !($config['public'] ?? false)) {
                continue;
            }

            $path = $config['path'];

            $ajaxSelectOptions = config($path . '.ajaxSelectOptions', []) ?? [];

            $validator = Validator::make($ajaxSelectOptions, [
                'label' => 'required',
//                'columns' => 'required',
                'model_class' => 'required',
            ]);

            if ($validator->fails() || !class_exists($ajaxSelectOptions['model_class'])) {
                continue;
            }

            $ajaxSelectOptions['model_morph'] = getMorphAlias($ajaxSelectOptions['model_class']);

            $ajaxSelectOptions['where'] = $ajaxSelectOptions['where'] ?? [];
            $ajaxSelectOptions['scopes'] = $config['scopes'] ?? [];

            $models[$ajaxSelectOptions['model_morph']] = $ajaxSelectOptions;
        }

        return $models;
    }
}

if (!function_exists('get_model_details')) {
    /**
     * @param $config
     * @param $object
     * @return array
     */
    function get_model_details($config, $object)
    {
        $modelTypeDetails = get_models($config)[$object->model_type] ?? [];

        return [
            'model' => $modelTypeDetails ? $modelTypeDetails['model_class'] : '',
            'columns' => $modelTypeDetails ? json_encode($modelTypeDetails['columns']) : '',
            'selected' => json_encode([$object->model_id]),
            'where' => $modelTypeDetails ? json_encode($modelTypeDetails['where']) : '',
            'scopes' => $modelTypeDetails ? json_encode($modelTypeDetails['scopes']) : '',
        ];
    }
}


if (!function_exists('getCleanedPhoneNumber')) {
    /**
     * @param $phoneNumber
     * @return string|string[]|null
     */
    function getCleanedPhoneNumber($phoneNumber)
    {
        return preg_replace("/[^0-9]/", '', $phoneNumber);
    }
}

if (!function_exists('getEmailPhoneLink')) {
    /**
     * @param $value
     * @param $type
     * @param $empty
     * @return mixed|string
     */
    function getEmailPhoneLink($value, $type, $empty = '-')
    {
        if (empty($value)) {
            return $empty;
        }

        switch ($type) {
            case 'mailto':
                $icon = '<i class="fa fa-fw fa-envelope-open-o" aria-hidden="true"></i> ';
                break;
            case 'tel':
                $icon = '<i class="fa fa-fw fa-mobile" aria-hidden="true"></i> ';
                break;
        }

        return HtmlElement('a', ['href' => "$type:$value", 'target' => '_blank'], $icon . $value);
    }
}


if (!function_exists('getCurrentTimeForFileName')) {
    function getCurrentTimeForFileName()
    {
        return now()->format('Y_dM_H_i');
    }
}

if (!function_exists('getRequestFiltersArray')) {
    /**
     * @param $requestFilters
     * @return array|string|string[]
     */
    function getRequestFiltersArray($requestFilters)
    {
        $array = explode("&", $requestFilters);

        $array = str_replace('#amp#', '&', $array);
        if (!(count($array) == 1 && $array[0] == "")) {
            $index = 0;

            foreach ($array as $key => $value) {
                $filter = explode("=", $value);
                preg_match_all('/(.*)\[(.*?)\]/', $filter[0], $matches);
                if (is_array($matches[0]) && (count($matches[0]) > 0)) {
                    if ($filter[1]) {
                        if (strpos($filter[1], ',') !== false) {
                            foreach (explode(',', $filter[1]) as $f) {
                                $array[$matches[1][0]][] = $f;
                            }
                        } else {
                            $nodeKey = empty(trim($matches[2][0], "'")) ? $index++ : trim($matches[2][0], "'");
                            $array[$matches[1][0]][$nodeKey] = $filter[1];
                        }
                    }
                } else {
                    if ($filter[1]) {
                        $array[$filter[0]] = $filter[1];
                    }
                }
                unset ($array[$key]);
            }
            return $array;
        } else {
            return [];
        }
    }
}
