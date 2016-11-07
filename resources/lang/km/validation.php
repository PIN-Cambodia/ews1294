<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    |  following language lines contain  default error messages used by
    |  validator class. Some of se rules have multiple versions such
    | as  size rules. Feel free to tweak each of se messages here.
    |
    */

    'accepted'             => ':attribute ត្រូវ​តែ​បាន​ទទួល​យក។',
    'active_url'           => ':attribute មិនមែនជា URL ត្រឹមត្រូវ។',
    'after'                => ':attribute ត្រូវ​តែ​ជា​កាលបរិច្ឆេទ​បន្ទាប់​ពី :date ។',
    'alpha'                => ':attribute អាច​មាន​តែ​អក្សរ​ប៉ុណ្ណោះ។',
    'alpha_dash'           => ':attribute អាច​មាន​តែ​អក្សរ លេខ និង​សញ្ញា(-) ប៉ុណ្ណោះ។',
    'alpha_num'            => ':attribute អាច​មាន​តែ​អក្សរ និង​លេខ​ប៉ុណ្ណោះ។',
    'array'                => ':attribute ត្រូវ​តែ​អាច​អារេ។',
    'before'               => ':attribute ត្រូវ​តែ​ជា​កាលបរិច្ឆេទ​មុន :date ។',
    'between'              => [
        'numeric' => ':attribute ត្រូវ​តែ​ស្ថិត​ក្នុង​ចន្លោះ :min និង :max ។',
        'file'    => ':attribute ត្រូវ​តែ​ស្ថិត​ក្នុង​ចន្លោះ :min និង :max គីឡូបៃ។',
        'string'  => ':attribute ត្រូវ​តែ​ស្ថិត​ក្នុង​ចន្លោះ :min និង :max តួអក្សរ។',
        'array'   => ':attribute ត្រូវ​តែ​មាន​​ក្នុង​ចន្លោះ :min និង :max ធាតុ។',
    ],
    'boolean'              => ':attribute វាល​ត្រូវ​តែ​ពិត ឬ​មិន​ពិត។',
    'confirmed'            => ':attribute មិនដូចគ្នាទេ',
    'date'                 => ':attribute មិនមែន​ជា​កាលបរិច្ឆេទ​ត្រឹមត្រូវ​ទេ។',
    'date_format'          => ':attribute មិន​ដូច​នឹង​ទ្រង់ទ្រាយ :format ទេ។',
    'different'            => ':attribute និង :or ត្រូវ​តែ​ខុស​គ្នា។',
    'digits'               => ':attribute ត្រូវ​តែ​មាន :digits ខ្ទង់។',
    'digits_between'       => ':attribute ត្រូវ​តែ​ស្ថិត​ក្នុង​ចន្លោះ :min និង :max ខ្ទង់។',
    'distinct'             => ':attribute វាល​មាន​តម្លៃ​ស្ទួន។',
    'email'                => ':attribute ត្រូវ​តែ​ជា​អាសយដ្ឋាន​អ៊ីមែល​ត្រឹមត្រូវ។',
    'exists'               => ':attribute ដែលបានជ្រើសរើសមិនត្រឹមត្រូវ។',
    'filled'               => ':attribute វាល​ដែល​ទាមទារ។',
    'image'                => ':attribute ត្រូវ​តែ​ជា​រូបភាព។',
    'in'                   => ':attribute ដែលបានជ្រើសរើសមិន​ត្រឹមត្រូវ។',
    'in_array'             => ':attribute វា​មិនមាន​ក្នុង :or ។',
    'integer'              => ':attribute ត្រូវ​តែ​ជា​ចំនួន​គត់។',
    'ip'                   => ':attribute ត្រូវ​តែ​ជា​អាសយដ្ឋាន IP ត្រឹមត្រូវ។',
    'json'                 => ':attribute ត្រូវ​តែ​ជា​ឃ្លា JSON ត្រឹមត្រូវ។',
    'max'                  => [
        'numeric' => ':attribute មិន​អាច​ធំជាង :max ។',
        'file'    => ':attribute មិន​អាច​ធំជាង :max គីឡូបៃ។',
        'string'  => ':attribute មិន​អាច​ច្រើន​ជាង :max តួអក្សរ។',
        'array'   => ':attribute មិន​អាច​មាន​ច្រើន​ជាង :max ធាតុ។',
    ],
    'mimes'                => ':attribute ត្រូវ​តែ​ជា​ឯកសារ​ប្រភេទ៖ :values ។',
    'min'                  => [
        'numeric' => ':attribute ត្រូវ​តែ​​តូចជាង :min ។',
        'file'    => ':attribute ត្រូវ​តែ​តូច​ជាង :min គីឡូបៃ។',
        'string'  => ':attribute ត្រូវ​តែ​តិចជាង :min តួអក្សរ។',
        'array'   => ':attribute ត្រូវ​តែ​តិច​ជាង :min ធាតុ។',
    ],
    'not_in'               => ':attribute ដែលបានជ្រើសរើសមិន​ត្រឹមត្រូវ។',
    'numeric'              => ':attribute ត្រូវ​តែ​ជា​លេខ។',
    'present'              => ':attribute ត្រូវ​តែ​មាន​។',
    'regex'                => 'ទម្រង់របស់ :attribute មិនត្រឹមត្រូវទេ',
    'required'             => 'វាល​ :attribute ​ត្រូវតែ​បញ្ចូល​',
    'required_if'          => 'វាល​ :attribute ត្រូ​វបាន​ទាមទារ​នៅ​ពេល :or ជា​តម្លៃ :value ។',
    'required_unless'      => 'វាល​ :attribute ​ត្រូវ​តែ​បាន​ទាមទារ​លុះត្រា​តែ :or ស្ថិត​ក្នុង :values ។',
    'required_with'        => 'វាល​ :attribute ​ត្រូវ​បាន​ទាមទារ​នៅ​ពេលមាន :values ។',
    'required_with_all'    => 'វាល​ :attribute ​ត្រូវ​បាន​ទាមទារ​នៅ​ពេលមាន :values ។',
    'required_without'     => 'វាល​ :attribute ត្រូវ​បាន​ទាមទារ​នៅ​ពេល​មិនមាន :values ។',
    'required_without_all' => 'វាល​ :attribute ​ត្រូវ​បាន​ទាមទារ​នៅ​ពេល​មិនមាន :values ។',
    'same'                 => ':attribute និង :or ត្រូវ​តែ​ដូច​គ្នា។',
    'size'                 => [
        'numeric' => ':attribute ត្រូវ​តែ​​ :size ។',
        'file'    => ':attribute ត្រូវ​តែ :size គីឡូបៃ។',
        'string'  => ':attribute ត្រូវ​តែ :size តួអក្សរ។',
        'array'   => ':attribute ត្រូវ​តែ​មាន :size ធាតុ។',
    ],
    'string'               => ':attribute ត្រូវតែ​ជា​ឃ្លា។',
    'timezone'             => ':attribute ត្រូវ​តែ​ជា​តំបន់​ត្រឹមត្រូវ។',
    'unique'               => ':attribute ត្រូវ​បាន​យក​រួច​ហើយ។',
    'url'                  => ':attribute ទ្រង់ទ្រាយ​មិន​ត្រឹមត្រូវ។',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using
    | convention "attribute.rule" to name  lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    |  following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];