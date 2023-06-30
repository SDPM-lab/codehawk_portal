<?php

namespace App\Helpers;

class ContextEntityValidation
{
    /**
     * 傳入驗證規則與資料，回傳結果陣列
     *
     * @param array $rules 規則
     * @param array $data 
     * @return array<string,boolean|array> key validation 為 bool ，判斷是否通過；key errors 為 array ，包含驗證不通過的所有訊息。 
     */
    public static function validationData(array $rules, array $data): array
    {
        $validation = \CodeIgniter\Config\Services::validation(null,false);
        $validation->setRules($rules);
        $returnData = [];
        $returnData["validation"] = $validation->run($data);
        $returnData["errors"] = $returnData["validation"] ? [] : $validation->getErrors();
        return $returnData;
    }
}
