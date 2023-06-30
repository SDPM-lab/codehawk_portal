<?php

namespace UserModule\App\ApplicationServices;

use UserModule\App\Config\Services;
use UserModule\App\Models\UserAuthModel;
use UserModule\UserContext\Entities\User;

class UserAppService
{

    /**
     * 產生 Token
     *
     * @param user $user 使用者實體
     * @param boolean $all 是否同時產生 access 與 refresh Token
     * @param string $type 只產生 access 或 refresh Token
     * @return array
     */
    public function generateAllTokens(user $user): array
    {
        $siginData = [
            "email" => $user->email,
            "first_name" => $user->firstName,
            "last_name" => $user->lastName,
            "key" => $user->key,
        ];
        $auth = Services::auth();
        $tokens = $auth->siginToken($siginData);
        $tokens["userKey"] = $user->key;
        return $tokens;
    }

    /**
     * 產生所選的 Token
     *
     * @param user $user 使用者實體
     * @param string $type 可以傳入 access 或 Refresh 字串
     * @return string
     */
    public function generateToken(user $user, string $type = "access"): string
    {
        $siginData = [
            "email" => $user->email,
            "first_name" => $user->firstName,
            "last_name" => $user->lastName,
            "key" => $user->key,
        ];
        $auth = Services::auth();
        if ($type == "access") {
            $token = $auth->createAccessToken($siginData);
        }

        if ($type == "refresh") {
            $token = $auth->createRefreshToken($siginData);
        }
        return $token;
    }

    /**
     * 將 Token 新建至資料庫
     *
     * @param user $user 使用者實體
     * @param array $tokens access 與 refresh Token
     * @param string $userAgent 使用者瀏覽器資訊
     * @param string $userIP 使用者 IP
     * @param UserAuthModel $userAuthModel
     * @return void
     */
    public function saveUserToken(
        user $user,
        array $tokens,
        string $userAgent,
        string $userIP
    ) {
        $userAuthModel = new UserAuthModel();
        $userAuthModel->insert([
            "user_key" => $user->key,
            "access_token" => $tokens["accessToken"],
            "refresh_token" => $tokens["refreshToken"],
            "user_agent" => $userAgent,
            "user_ip" => $userIP,
        ]);
    }

    public function updateUserToken(
        int $authKey,
        string $userIP,
        string $accessToken = null,
        string $refreshToken = null
    ) {
        $updateData = [];
        if ($accessToken) {
            $updateData["access_token"] = $accessToken;
        }

        if ($refreshToken) {
            $updateData["refresh_token"] = $refreshToken;
        }

        $updateData["user_ip"] = $userIP;
        $userAuthModel = new UserAuthModel();
        $userAuthModel->update($authKey, $updateData);
    }

    public function deleteUserToken(int $authKey) {
        $userAuthModel = new UserAuthModel();
        $userAuthModel->delete($authKey);
    }

    /**
     * 驗證使用者傳入的登入資訊是否存在於資料庫。
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @param string $userAgent
     * @return array<string,int>|null 若擁有，則回傳鍵值陣列，包含 authKey 與 userKey
     */
    public function verifyTokens(
        string $accessToken,
        string $refreshToken,
        ?string $userAgent = null
    ): ?array{
        $userAuthModel = new UserAuthModel();
        $userAuthModel->where('refresh_token', $refreshToken)
            ->where('access_token', $accessToken);
        if ($userAgent) {
            $userAuthModel->where('user_agent', $userAgent);
        }

        $authResult = $userAuthModel->findAll();
        if (count($authResult) > 0) {
            return [
                "authKey" => $authResult[0]["key"],
                "userKey" => $authResult[0]["user_key"]
            ];
        }
        return null;
    }
}
