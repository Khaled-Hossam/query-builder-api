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

        $content = file_get_contents($request['input']->getRealPath() );

        $queryConditions = json_decode( $content, true) ;
        foreach ($queryConditions as  $condition) {
            $this->buildQuery2($condition);
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

    /**
     * append To Response Query
     */
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

}