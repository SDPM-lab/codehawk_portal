<?php

namespace UserModule\UserContext\Repositories;

use UserModule\App\Entites\UserEntity;
use UserModule\App\Models\UserModel;
use UserModule\UserContext\Entities\User;

class UserRepository
{

    /**
     * 將使用者儲存至資料庫
     *
     * @param User $user 使用者實體
     * @return integer 被建立的使用者資料庫主鍵
     */
    public function createUser(User $user): int
    {
        //將領域User實體轉換為 CodeIgniter4 ORM 實體
        $userEntity = new UserEntity();
        $userEntity->email = $user->email;
        $userEntity->last_name = $user->lastName;
        $userEntity->first_name = $user->firstName;

        //新建使用者模型與儲存實體
        $userModel = new UserModel();
        $userKey = $userModel->insert($userEntity);

        return $userKey;
    }

    /**
     * 傳入使用者信箱取回使用者實體
     * 若使用者不存在於資料庫中，則回傳 null
     *
     * @param string $email 信箱
     * @return UserModule\UserContext\Entities\User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        $userModel = new UserModel();
        $result = $userModel->where('email', $email)->findAll();
        if (count($result) == 0) {
            return null;
        } else {
            $userEntity = $result[0];
            $user = new User(
                $userEntity->first_name,
                $userEntity->last_name,
                $userEntity->email,
                $userEntity->key,
                $userEntity->img,
                $userEntity->created_at,
                $userEntity->updated_at
            );
            return $user;
        }
    }

    /**
     * 傳入使用者信箱取回使用者實體
     * 若使用者不存在於資料庫中，則回傳 null
     *
     * @param string $email 信箱
     * @return UserModule\UserContext\Entities\User|null
     */
    public function getUserByKey(int $key): ?User
    {
        $userModel = new UserModel();
        $result = $userModel->where('key', $key)->findAll();
        if (count($result) == 0) {
            return null;
        } else {
            $userEntity = $result[0];
            $user = new User(
                $userEntity->first_name,
                $userEntity->last_name,
                $userEntity->email,
                $userEntity->key,
                $userEntity->img,
                $userEntity->created_at,
                $userEntity->updated_at
            );
            return $user;
        }
    }

}
