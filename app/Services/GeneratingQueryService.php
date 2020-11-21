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
        // if file doesn't decoded successfuly, so it doesn't have valid data
        if(!$queryConditions){
            return false;
        }
        // if first element is array, so there is more than one condition,
        // else, there is only one condition
        $keys = array_keys($queryConditions);
        if(is_array($queryConditions[$keys[0]])){
            foreach ($queryConditions as  $condition) {
                $this->buildQuery($condition);
            }
        }
        else{
            $this->buildQuery($queryConditions);
        }

        // the final reponse query
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
     * process the condition, and update response data
     */
    private function buildQuery($condition){
        $this->responseElement = [
            $condition['column'] => [
                OperatorEnum::OPERATION_MAPPING[$condition['operation']] => $condition['value'][0]
            ]
        ];

        if($condition['sub_operation'] == SubOperatorEnum::AND){
            // add current element to -and elements-
            $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
        }

        elseif($condition['sub_operation'] == SubOperatorEnum::OR){
            $this->addToOrElements();
        }

        elseif($condition['sub_operation'] == null){
            if($this->responseOrElements){
                $this->addToOrElements();

            }
            elseif($this->responseAndElements){
                // add current element to -and elements-
                $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;
            }
        }

    }





    private function addToOrElements(){
        if($this->responseAndElements){
            // add current element to -and elements-
            $this->responseAndElements[SubOperatorEnum::OPERATION_MAPPING['and']][] = $this->responseElement;

            // add -and elements- to the -or elemments-
            $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseAndElements;
            
            // clear -and elements- (because the sub_opration is or, so this element
            // would be the last elemnt in the current -and elements-)
            $this->responseAndElements = [];
        }
        // if there is no -and elements- so just add current element to -or elements-
        else{
            $this->responseOrElements[SubOperatorEnum::OPERATION_MAPPING['or']][] = $this->responseElement;
        }
    }

}