<?php

namespace UserModule\UserContext\DomainServices;

use UserModule\UserContext\Entities\User;
use UserModule\UserContext\Repositories\UserRepository;
use UserModule\UserContext\Repositories\UserServicesRepository;

class UserDomainService
{

    /**
     * 使用者資料存取類別
     *
     * @var \UserModule\UserContext\Repositories\UserRepository
     */
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * 傳入資料尋找是否已有這筆使用者資料，若有則回傳使用者實體。
     * 若無，則新建後回傳使用者實體。
     *
     * @param string $email 信箱
     * @param string $firstName 名
     * @param string $lastName 姓
     * @return \UserModule\UserContext\Entities\User
     */
    public function findUser(
        string $email,
        string $firstName,
        string $lastName
    ): User {
        //尋找是否已有使用者存在於儲存庫，若有則直接回傳
        $user = $this->userRepository->getUserByEmail($email);
        if ($user instanceof User) {
            return $user;
        }
        //新建 User 實體
        $user = new User($firstName, $lastName, $email);
        //新建 User 進儲存庫，並將新的 Key 更新至 User 實體後回傳
        $newUserkey = $this->userRepository->createUser($user);
        $user->key = $newUserkey;
        return $user;
    }

    /**
     * 傳入使用者主鍵，若存在則回傳使用者實體。
     * 若無，則回傳 Null。
     *
     * @param integer $key 使用者主鍵
     * @return User|null
     */
    public function findUserKey(int $key): ?User
    {
        $user = $this->userRepository->getUserByKey($key);
        return $user;
    }

    /**
     * 在多個服務中新建相同帳號密碼的使用者
     *
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @return boolean
     */
    public function createServicesUser(
        string $email,
        string $firstName,
        string $lastName,
        string $password
    ): bool {
        $user = new User($firstName, $lastName, $email);
        $userServices = new UserServicesRepository();

        //註冊 git 帳號
        $git = $userServices->createGitUser($user, $password)->do();
        if (!$git->isSuccess()) {
            log_message('critical', $git->getMeaningData());
            return false;
        }

        return true;
    }

}
