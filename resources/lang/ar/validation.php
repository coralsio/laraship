<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'message' => 'حصل خطأ في عملية الادخال',
    'accepted'              => ':attribute يجب أن تكون مقبولة.',
    'active_url'            => ':attribute ليس رابط صحيح.',
    'after'                 => ':attribute يجب أن يكون تاريخ بعد :date.',
    'alpha'                 => ':attribute يحتوى على حروف فقط.',
    'alpha_dash'            => ':attribute يجب أن يحتوى فقط على أرقام وحروف وداشز.',
    'alpha_num'             => ':attribute يجب أن يحتوى فقط على أرقام وحروف.',
    'array'                 => ':attribute يجب أن يكون مصفوفة.',
    'before'                => ':attribute يجب أن يكون تاريخ قبل :date.',
    'between'               => [
        'array'     => ':attribute يجب أن يكون بين :min و :max عناصر.',
        'file'      => ':attribute يجب أن يكون بين :min و :max كيلو بايت.',
        'numeric'   => ':attribute يجب أن يكون بين :min و :max.',
        'string'    => ':attribute يجب أن يكون بين :min و :max حروف.',
    ],
    'boolean'               => 'حقل :attribute يجب أن يكون true أو false.',
    'confirmed'             => ':attribute ﻻ تتطابق مع التأكيد.',
    'date'                  => ':attribute ليس تارسخ صحيح.',
    'date_format'           => ':attribute ﻻ يتوافق مع الصيغة :format.',
    'different'             => ':attribute و :other يجب أن يكونا مخلفيان.',
    'digits'                => ':attribute يجب أن يكون :digits أرقام.',
    'digits_between'        => ':attribute يجب أن يكون بين :min و :max أرقام.',
    'email'                 => ':attribute يجب أن يكونبريد صحيح.',
    'exists'                => ':attribute المحدد غير صحيح.',
    'filled'                => 'حقل :attribute مطلوب.',
    'image'                 => ':attribute يجب أن يكون صورة.',
    'in'                    => 'المختار :attribute غير صحيح.',
    'integer'               => ':attribute يجب أن يكون رقم.',
    'ip'                    => ':attribute يجب أن يكون صيغة IP صحيحة.',
    'json'                  => ':attribute يجب أن يكون صيغة JSON.',
    'max'                   => [
        'array'     => ':attribute قد ﻻ يحتوى أكثر من :max عناصر.',
        'file'      => ':attribute قد ﻻ يكون أكبر من :max كيلو بايت.',
        'numeric'   => ':attribute قد ﻻ يكون أكبر من :max.',
        'string'    => ':attribute قد ﻻ يكون أكبر من :max حروف.',
    ],
    'mimes'                 => ':attribute يجب أن يكون ملف من صيغة: :values.',
    'min'                   => [
        'array'     => ':attribute يجب أن يحتوى على اﻷقل :min عناصر.',
        'file'      => ':attribute يجب أن يكون على اﻷقل :min كيلو بايت.',
        'numeric'   => ':attribute يجب أن يكون على اﻷقل :min.',
        'string'    => ':attribute يجب أن يكون على اﻷقل :min حروف.',
    ],
    'not_in'                => ':attribute المحدد غير صحيح.',
    'numeric'               => ':attribute يجب أن يكون رقم.',
    'recaptcha'             => 'حقل :attribute غير صحيح.',
    'regex'                 => ':attribute صيغة غير صحيحة.',
    'required'              => 'حقل :attribute مطلوب.',
    'required_if'           => 'حقل :attribute مطلوب عندما :other يكون :value.',
    'required_unless'       => 'حقل :attribute مطلوب مالم :other هو فى :values.',
    'required_with'         => 'حقل :attribute مطلوب عندما :values موجود.',
    'required_with_all'     => 'حقل :attribute مطلوب عندما :values موجود.',
    'required_without'      => 'حقل :attribute مطلوب عندما :values غير موجود.',
    'required_without_all'  => 'حقل :attribute مطلوب عندما أيا من :values موجودة.',
    'same'                  => ':attribute و :other يجب أن يتطابقا.',
    'size'                  => [
        'array'     => ':attribute يجب أن يحتوى :size عناصر.',
        'file'      => ':attribute يجب أن يكون :size كيلو بايت.',
        'numeric'   => ':attribute يجب أن يكون :size.',
        'string'    => ':attribute يجب أن يكون :size حروف.',
    ],
    'string'                => ':attribute يجب أن يكون نص.',
    'timezone'              => ':attribute يجب أن تكون منطقة صالحة.',
    'unique'                => ':attribute بالفعل موجود.',
    'url'                   => ':attribute صيغة غير صحيحة.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'    => [
        'attribute-name'    => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes'    => [],
];
