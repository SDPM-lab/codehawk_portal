<?php

namespace App\Anser\ServicesInterface;

use SDPMlab\Anser\Service\Action;

interface UserServiceInterface
{

    /**
     * 取得使用者清單，可以依據服務的不同實作出分頁的功能。
     *
     * @return void
     */
    public function getUsers(): Action;

    /**
     * 取得單一使用者資料，若取得失敗則 MeaningData 將為 Null
     *
     * @param string $userEmail
     * @return void
     */
    public function getUser(string $userEmail): Action;

    /**
     * 新建服務使用者。
     * 以 Action 的 isSuccess 方法確認是否成功。
     * 若成功 Action 的 MeaningData 將會是新建成功的使用者 ID 。
     * 若失敗 Action 的 MeaningData 將會儲存失敗相關訊息。
     * 
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @return void
     */
    public function createUser(
        string $email,
        string $firstName,
        string $lastName,
        string $password
    ): Action;

    /**
     * 以 ID 刪除使用者
     *
     * @return void
     */
    public function deleteUser($userID): Action;
}
