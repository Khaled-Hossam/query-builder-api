<?php

namespace App\Http\Enums;


class SubOperatorEnum 
{

    const AND = 'and';
    const OR = 'or';

    const OPERATION_MAPPING = [
        self::AND => '$and',
        self::OR => '$or',
    ];

}
