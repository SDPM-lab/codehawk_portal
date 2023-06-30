<?php

namespace App\Anser\Services\Gitlab;

use SDPMlab\Anser\Service\SimpleService;
use App\Anser\Filters\GitLabActionFilter;
use SDPMlab\Anser\Service\Action;
use App\Anser\ServicesInterface\UserServiceInterface;

class GitlabUserService extends SimpleService implements UserServiceInterface {
    
    protected $serviceName = "gitlab_service";
    protected $filters = [
        "before" => [GitLabActionFilter::class],
        "after" => [],
    ]; 

    /**
     * 取得使用者清單
     *
     * @param integer $page 目前頁數
     * @param integer $prePage 每頁回傳上限
     * @return \SDPMlab\Anser\Service\Action Action 實體
     */
    public function getUsers(int $page = 1, int $prePage = 10): Action
    {
        $action = $this->getAction("GET","/api/v4/users")
            ->addOption("query",[
                'page' => $page,
                'prePage' => $prePage
            ])
            ->setMeaningDataHandler(function(Action $runTimeAction){
                $result = $runTimeAction->getResponse()->getBody()->getContents();
                return json_decode($result,true);
            });
        return $action;
    }

    /**
     * 取得單一使用者資料，若取得失敗則 MeaningData 將為 Null
     *
     * @param string $userEmail 使用者信箱
     * @return \SDPMlab\Anser\Service\Action Action 實體 
     */
    public function getUser(string $userEmail): Action
    {
        $action = $this->getAction("GET","/api/v4/users?search={$userEmail}")
            ->setMeaningDataHandler(function(Action $runTimeAction){
                $result = $runTimeAction->getResponse()->getBody()->getContents();
                $data =  json_decode($result,true);
                if(count($data) > 0){
                    $runTimeAction->setMeaningData($data[0]);
                }else{
                    $runTimeAction->setMeaningData(null);
                }
            });
        return $action;
    }

    /**
     * 新建 gitlab 使用者。
     * 以 Action 的 isSuccess 方法確認是否成功。
     * 若成功 Action 的 MeaningData 將會是新建成功的使用者 ID 。
     * 若失敗 Action 的 MeaningData 將會儲存失敗相關訊息。
     *
     * @param string $email 信箱
     * @param string $firstName 名
     * @param string $lastName 姓
     * @param string $password 密碼
     * @return \SDPMlab\Anser\Service\Action Action 實體 
     */
    public function createUser(
        string $email,
        string $firstName,
        string $lastName,
        string $password
    ): Action{
        $action = $this->getAction("POST","/api/v4/users")
            ->addOption("json",[
                "email" => $email,
                "username" => $firstName.$lastName,
                "name" => $firstName.' '.$lastName,
                "password" => $password,
                "reset_password" => false,
                "force_random_password" => false,
                "skip_confirmation" => true
            ])
            ->setMeaningDataHandler(function(Action $runTimeAction){
                $result = $runTimeAction->getResponse()->getBody()->getContents();
                $newUserID = json_decode($result,true)["id"];
                return $newUserID;
            })
            ->set4XXErrorHandler(function(Action $runTimeAction){
                $result = $runTimeAction->getResponse()->getBody()->getContents();
                $errorMessage = json_decode($result,true)["error"];
                $runTimeAction->setMeaningData($errorMessage);
            });
        return $action;
    }

    /**
     * 以 ID 刪除 Gitlab 使用者
     *
     * @param integer $userID
     * @return \SDPMlab\Anser\Service\Action Action 實體 
     */
    public function deleteUser($userID): Action
    {
        $action = $this->getAction("DELETE","/api/v4/users/{$userID}")
            ->addOption("json",[
                'hard_delete ' => true
            ]);
        return $action;
    }

}