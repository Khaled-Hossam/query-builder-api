<?php

namespace App\Http\Enums;


class OperatorEnum 
{

    const IS_EQUAL = 'is_equal';
    const NOT_EQUAL = 'not_equal';
    const GREATER_THAN = 'greater_than';
    const LESS_THAN = 'less_than';
    const GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';
    const LESS_THAN_OR_EQUAL = 'less_than_or_equal';

    // the key is : the oprator which come as input 
    // and the value : is the corresponding oprator whcih will be returned in the final output
    const OPERATION_MAPPING = [
        self::IS_EQUAL => '$eq',
        self::NOT_EQUAL => '$ne',
        self::GREATER_THAN => '$gt',
        self::LESS_THAN => '$lt',
        self::GREATER_THAN_OR_EQUAL => '$gte',
        self::LESS_THAN_OR_EQUAL => '$lte',
    ];

}
