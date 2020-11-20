<?php

namespace App\Services;

use App\Http\Enums\OperatorEnum;
use App\Http\Enums\SubOperatorEnum;

class GeneratingQueryService{

    private $responseQuery = [];

    private $responseElement;
    private $responseAndElements = [];
    private $responseOrElements = [];

    public function execute(array $request){
        // $this->responseQuery = collect();

        // $content = file_get_contents($request['input']->);
        $content = file_get_contents($request['input']->getRealPath() );
        $content = json_decode( $content, true) ;
        foreach ($content as  $query) {
            $this->buildQuery2($query);
        }

        if($this->responseOrElements){
            $this->responseQuery = $this->responseOrElements;
        }
        elseif($this->responseAndElements){
            $this->responseQuery = $this->responseAndElements;
        }
        else{
            $this->responseQuery = $this->responseElement;
        }
        return $this->responseQuery;
    }


    private function buildQuery2($query){
        $this->responseElement = [
            $query['column'] => [
                OperatorEnum::OPERATION_MAPPING[$query['operation']] => $query['value'][0]
            ]
        ];

        if($query['sub_operation'] == SubOperatorEnum::AND){

            // if($this->responseOrElements){
            //     if($this->responseAndElements){
            //         // add current element to and elements
            //         $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
    
            //         $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseAndElements;
            //         // $this->responseAndElements = [];
            //     }
            //     else{
            //         $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;

            //         $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseElement;
            //     }
            // }
            // else{
                $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
            // }
        }

        elseif($query['sub_operation'] == SubOperatorEnum::OR){
            if($this->responseAndElements){
                // add current element to and elements
                $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;

                $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseAndElements;
                $this->responseAndElements = [];
            }
            else{
                $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseElement;
            }
        }
        elseif($query['sub_operation'] == null){
            if($this->responseOrElements){
                if($this->responseAndElements){
                    // add current element to and elements
                    $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
    
                    $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseAndElements;
                    $this->responseAndElements = [];
                }
                else{
                    $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseElement;
                }
            }
            elseif($this->responseAndElements){
                // add current element to and elements
                $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
                $this->responseAndElements = [];
            }
        }

    }

    /**
     * append To Response Query
     */
    private function buildQuery($query){
        // $this->responseQuery[] ;
        if($query['sub_operation'] == SubOperatorEnum::AND ){
            $opeartionKey = SubOperatorEnum::OPERATION_MAPPING[$query['sub_operation']];
            if(key_exists(SubOperatorEnum::OPERATION_MAPPING['or'], $this->responseQuery) ){
                end($this->responseQuery[SubOperatorEnum::OPERATION_MAPPING['or']]);
                $lasOrKey = key($this->responseQuery[SubOperatorEnum::OPERATION_MAPPING['or']]);

                $this->responseQuery[SubOperatorEnum::OPERATION_MAPPING['or']][$lasOrKey][SubOperatorEnum::OPERATION_MAPPING['and']][] = [
                    $query['column'] => [
                        OperatorEnum::OPERATION_MAPPING[$query['operation']] => $query['value'][0]
                    ]
                    // $query['column'][$query[OperatorEnum::OPERATION_MAPPING[$query['operation']]] ] => $query['value'][0]
                ];
            }
            else{
                $this->responseQuery[SubOperatorEnum::OPERATION_MAPPING['and']][] = [
                    $query['column'] => [
                        OperatorEnum::OPERATION_MAPPING[$query['operation']] => $query['value'][0]
                    ]
                    // $query['column'][$query[OperatorEnum::OPERATION_MAPPING[$query['operation']]] ] => $query['value'][0]
                ];
                // $this->responseQuery[$opeartionKey][] = $condition;
            }

        }
        elseif($query['sub_operation'] == SubOperatorEnum::OR){
            $opeartionKey = SubOperatorEnum::OPERATION_MAPPING[$query['sub_operation']];
            if(key_exists($opeartionKey, $this->responseQuery) ){
                end($this->responseQuery[$opeartionKey]);
                $lastOrKey = key($this->responseQuery[$opeartionKey]);

                $this->responseQuery[$opeartionKey][$lastOrKey][SubOperatorEnum::OPERATION_MAPPING['and']][] = [
                    $query['column'] => [
                        OperatorEnum::OPERATION_MAPPING[$query['operation']] => $query['value'][0]
                    ]
                ];
            }
            else{
                $currentContent = $this->responseQuery;
                // dd($currentContent);
                $this->responseQuery = [];
                $this->responseQuery[$opeartionKey] = $currentContent; 

                $this->responseQuery[$opeartionKey][SubOperatorEnum::OPERATION_MAPPING['and']][] = [
                    // SubOperatorEnum::OPERATION_MAPPING['and'] => [
                        $query['column'] => [
                            OperatorEnum::OPERATION_MAPPING[$query['operation']] => $query['value'][0]
                        ]
                    // ]
                ];
            }
        }
        // elseif($query['sub_operation'] == null){

        // }
    }
}