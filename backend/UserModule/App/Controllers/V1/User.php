<?php

namespace UserModule\App\Controllers\V1;

use CodeIgniter\RESTful\ResourceController;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;
use UserModule\App\ApplicationServices\UserAppService;
use UserModule\UserContext\DomainServices\UserDomainService;
use App\Anser\Services\Gitlab\GitlabUserService;

class User extends ResourceController
{

    protected $format = "json";

    /**
     * @var \Stevenmaguire\OAuth2\Client\Provider\Keycloak
     */
    private $provider;
    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    public function __construct()
    {
        $this->session = session();
        $this->provider = new Keycloak([
            'authServerUrl' => env('AUTH_SERVER_URL'),
            'realm' => env('REALM'),
            'clientId' => env('CLIENT_ID'),
            'clientSecret' => env('CLIENT_SECRET'),
            'redirectUri' => base_url('/api/v1/user/login'),
        ]);
    }

    /**
     * service login
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=148
     * @return mixed
     */
    public function serviceLogin()
    {
		
    }

    /**
     * signup
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=151
     * @return mixed
     */
    public function serviceSignup()
    {
		$jsonData = $this->request->getJSON(true);
        $userDomain = new UserDomainService();
        $createResult = $userDomain->createServicesUser($jsonData["email"],$jsonData["first_name"],$jsonData["last_name"],$jsonData["password"]);
        if($createResult){
            return $this->respond([
                "status_code" => "SUCCESS"
            ]);
        }else{
            return $this->respond(["statusCode" => "User016", "msg" => "某個服務註冊失敗，詳細錯誤將寫入至 Log 檔"], 500);
        }
    }

    /**
     * refresh
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=149
     * @return mixed
     */
    public function refresh()
    {
        $accessToken = $this->request->getHeaderLine("X-Access-Token");
        $refreshToken = $this->request->getHeaderLine("X-Refresh-Token");
        $userAgent = $this->request->getUserAgent();
        $userIP = $this->request->getIPAddress();
        if ($refreshToken === "" || $accessToken === "") {
            return $this->respond(["statusCode" => "Auth001", "msg" => "傳入欄位具有缺失"], 400);
        }

		$userAppService = new UserAppService();
        //驗證使用者傳入的登入資訊是否存在於資料庫。
		try {
			$verify =  $userAppService->verifyTokens($accessToken, $refreshToken, $userAgent);
			if(!$verify){
				return $this->respond(["statusCode" => "Auth007", "msg" => "找不到使用者登入資訊"], 404);
			}
			$userKey = $verify["userKey"];
			$authKey = $verify["authKey"];
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        //取得 CodeHawk 使用者資料
        $userDomain = new UserDomainService();
        try {
            $user = $userDomain->findUserKey($userKey);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        //簽發新 Access Token
		try {
            $accessToken = $userAppService->generateToken($user, "access");
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "Auth006", "msg" => "驗證程式庫運作過程發生未知錯誤"], 400);
        }

        //更新資料庫
        try {
			$userAppService->updateUserToken($authKey, $userIP, $accessToken);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode" => "SUCCESS",
            "msg" => "更新成功",
            "accessToken" => $accessToken,
        ], 200);
    }

    /**
     * login
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=154
     * @return mixed
     */
    public function login()
    {
        //判斷是否為 keycloak 跳轉
        if (!$this->request->getGet("code")) {
            // If we don't have an authorization code then get one
            $authUrl = $this->provider->getAuthorizationUrl();
            $this->session->set('oauth2state', $this->provider->getState());
            return redirect()->to($authUrl);
        }

        //若為 keycloak 跳轉則開始處理
        if (!$this->request->getGet("state") ||
            ($this->request->getGet("state") != $this->session->get("oauth2state"))
        ) {
            $this->session->remove("oauth2state");
            return "Invalid state, make sure HTTP sessions are enabled.";
        } else {
            return redirect()->to(env("FRONTEND_URL") . "?code={$this->request->getGet("code")}");
        }
    }

    /**
     * login
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=145
     * @return mixed
     */
    public function getToken()
    {
        $code = $this->request->header("X-Oidc-Code")->getValue();
        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $code,
            ]);
        } catch (\Exception $e) {
            return $this->failUnauthorized('Failed to get resource owner: ' . $e->getMessage());
        }
        try {
            $tokenResourse = $this->provider->getResourceOwner($token);
            $userTokenData = $tokenResourse->toArray();
        } catch (\Exception $e) {
            return $this->failForbidden('Failed to get resource owner: ' . $e->getMessage());
        }

        //取得 CodeHawk 使用者資料
        $userDomain = new UserDomainService();
        try {
            $user = $userDomain->findUser($userTokenData['email'], $userTokenData['given_name'], $userTokenData['family_name']);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        $userAppService = new UserAppService();

        //簽發Token
        try {
            $tokens = $userAppService->generateAllTokens($user);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "Auth006", "msg" => "驗證程式庫運作過程發生未知錯誤"], 400);
        }

        //Auth資料儲存至資料庫
        $userAgent = $this->request->header('user-agent')->getValue();
        $userIP = $this->request->getIPAddress();
        try {
            $userAppService->saveUserToken($user, $tokens, $userAgent, $userIP);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        return $this->respond([
            "statusCode" => "Created",
            "msg" => "建立成功",
            "data" => $tokens,
        ], 201);
    }

    /**
     * show
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=147
     * @param  string $userKey 使用者主鍵
     * @return mixed
     */
    public function show($userKey = "")
    {
        $user = \UserModule\App\Config\Services::auth()->getUser();
		if($user->key == (int)$userKey){
			return $this->respond([
				"status_code" => "SUCCESS",
				"data" => [
					"key" => $user->key,
					"first_name" => $user->firstName,
					"last_name" => $user->lastName,
					"email" => $user->email
				]
			]);
		}else{
			return $this->failUnauthorized("已登入，無存取這個資源的權限。");
		}
    }

    /**
     * delete
     *
     * https://rap2.sdpmlab.org/organization/repository/editor?id=18&itf=150
     * @return mixed
     */
    public function delete($accessToken = null)
    {
		$accessToken = $this->request->getHeaderLine("X-Access-Token");
        $refreshToken = $this->request->getHeaderLine("X-Refresh-Token");
        if ($refreshToken === "" || $accessToken === "") {
            return $this->respond(["statusCode" => "Auth001", "msg" => "傳入欄位具有缺失"], 400);
        }

		$userAppService = new UserAppService();
        //驗證使用者傳入的登入資訊是否存在於資料庫。
		try {
            $verify = $userAppService->verifyTokens($accessToken, $refreshToken);
			if(!$verify){
				return $this->respond(["statusCode" => "Auth007", "msg" => "找不到使用者登入資訊"], 404);
			}
			$authKey = $verify["authKey"];
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        //刪除登入資料
        try {
            $userAppService->deleteUserToken($authKey);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode" => "DBError", "msg" => "資料庫處理失敗"], 400);
        }

        $logout = [
            "logout_url" => $this->provider->getLogoutUrl([
				'redirect_uri' => env("FRONTEND_URL"),
			]),
            "status_code" => "SUCCESS",
        ];
        return $this->respond($logout);
    }
}
