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

    'accepted' => 'Вы должны принять :attribute.',
    'accepted_if' => 'Вы должны принять :attribute, если :other имеет значение :value.',
    'active_url' => 'Поле :attribute содержит недействительный URL.',
    'after' => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal' => 'В поле :attribute должна быть дата равной или после :date.',
    'alpha' => 'Поле :attribute может содержать только буквы.',
    'alpha_dash' => 'Поле :attribute может содержать только буквы, цифры и дефис.',
    'alpha_num' => 'Поле :attribute может содержать только буквы и цифры.',
    'array' => 'Поле :attribute должно быть массивом.',
    'before' => 'В поле :attribute должна быть дата до :date.',
    'before_or_equal' => 'В поле :attribute должна быть дата равная или до :date.',
    'between' => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file' => 'Размер файла в поле :attribute должен быть между :min и :max КБ.',
        'string' => 'Количество символов в поле :attribute должно быть между :min и :max.',
        'array' => 'Количество элементов в поле :attribute должно быть между :min и :max.',
    ],
    'boolean' => 'Поле :attribute должно иметь значение логического типа.',
    'confirmed' => 'Поле :attribute не совпадает с подтверждением.',
    'current_password' => 'Пароль указан неверно.',
    'date' => 'Поле :attribute не является датой.',
    'date_equals' => 'Поле :attribute должно быть датой, равной значению поля :date.',
    'date_format' => 'Поле :attribute не соответствует формату :format.',
    'declined' => 'Поле :attribute не должно быть принято.',
    'declined_if' => 'Поле :attribute не должно быть принято, если поле :other принимает значение :value.',
    'different' => 'Поле :attribute должно отличаться от :other.',
    'digits' => 'Длина цифрового поля :attribute должна быть :digits.',
    'digits_between' => 'Длина цифрового поля :attribute должна быть между :min и :max.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'email' => 'Поле :attribute должно быть действительным электронным адресом.',
    'ends_with' => 'Поле :attribute должно оканчиваться на одно из значений: :values.',
    'enum' => 'Выбранное значение для :attribute некорректно.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'file' => 'Поле :attribute должно быть файлом.',
    'filled' => 'Поле :attribute обязательно для заполнения.',
    'gt' => [
        'array' => 'Количество элементов в поле :attribute должно быть более :value.',
        'file' => 'Размер файла в поле :attribute должен быть более :value КБ.',
        'numeric' => 'Поле :attribute должно быть более :value.',
        'string' => 'Количество символов в поле :attribute должно быть более :value.',
    ],
    'gte' => [
        'array' => 'Количество элементов в поле :attribute должно быть более или равным :value.',
        'file' => 'Размер файла в поле :attribute должен быть более или равным :value КБ.',
        'numeric' => 'Поле :attribute должно быть более или равным :value.',
        'string' => 'Количество символов в поле :attribute должно быть более или равным :value.',
    ],
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранное значение для :attribute ошибочно.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'ip' => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4' => 'Поле :attribute должно быть действительным IP-адресом типа IPv4.',
    'ipv6' => 'Поле :attribute должно быть действительным IP-адресом типа IPv6.',
    'json' => 'Поле :attribute должно быть JSON строкой.',
    'lt' => [
        'array' => 'Количество элементов в поле :attribute должно быть менее :value.',
        'file' => 'Размер файла в поле :attribute должен быть менее :value КБ.',
        'numeric' => 'Поле :attribute должно быть менее :value.',
        'string' => 'Количество символов в поле :attribute должно быть менее :value.',
    ],
    'lte' => [
        'array' => 'Количество элементов в поле :attribute должно быть менее или равным :value.',
        'file' => 'Размер файла в поле :attribute должен быть менее или равным :value КБ.',
        'numeric' => 'Поле :attribute должно быть менее или равным :value.',
        'string' => 'Количество символов в поле :attribute должно быть менее или равным :value.',
    ],
    'mac_address' => 'Поле :attribute должно быть действительным MAC адресом.',
    'max' => [
        'numeric' => 'Поле :attribute не может быть более :max.',
        'file' => 'Размер файла в поле :attribute не может быть более :max КБ.',
        'string' => 'Количество символов в поле :attribute не может превышать :max.',
        'array' => 'Количество элементов в поле :attribute не может превышать :max.',
    ],
    'mimes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes' => 'Файл :attribute должен быть одним из допустимых типов: :values.',
    'min' => [
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'file' => 'Размер файла в поле :attribute должен быть не менее :min КБ.',
        'string' => 'Количество символов в поле :attribute должно быть не менее :min.',
        'array' => 'Количество элементов в поле :attribute должно быть не менее :min.',
    ],
    'multiple_of' => 'Поле :attribute должно быть произведением значений :value.',
    'not_in' => 'Выбранное значение для :attribute ошибочно.',
    'not_regex' => 'Поле :attribute имеет ошибочный формат',
    'numeric' => 'Поле :attribute должно быть числом.',
    'password' => 'Пароль указан некорректно.',
    'present' => 'Поле :attribute должно присутствовать.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено, если поле :other принимает значение :value.',
    'prohibited_unless' => 'Поле :attribute запрещено, когда :other не равно :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие в запросе поля :other.',
    'regex' => 'Поле :attribute имеет ошибочный формат',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Поле :attribute должно содержать элементы из: :values.',
    'required_if' => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_unless' => 'Поле :attribute обязательно для заполнения, когда :other не равно :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_without' => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same' => 'Значение :attribute должно совпадать с :other.',
    'size' => [
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'file' => 'Размер файла в поле :attribute должен быть равен :size КБ.',
        'string' => 'Количество символов в поле :attribute должно быть равным :size.',
        'array' => 'Количество элементов в поле :attribute должно быть равным :size.',
    ],
    'starts_with' => 'Поле :attribute должно начинаться со следующих значений: :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'timezone' => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'uploaded' => 'Загрузка поля :attribute не удалась.',
    'url' => 'Поле :attribute имеет ошибочный формат.',
    'uuid' => 'Поле :attribute должно иметь формат UUID.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
