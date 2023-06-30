<?php
namespace UserModule\App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use UserModule\App\Config\Services;
use CodeIgniter\API\ResponseTrait;

class PrivateResourceAuth implements FilterInterface
{
    use ResponseTrait;
    
    /**
     * 響應
     *
     * @var \CodeIgniter\HTTP\Response
     */
    protected $response;
    protected $format = 'json';

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->response = Services::response();
        $request = Services::request();
        if($accessToken = $request->header("X-Access-Token")){
            $accessToken = $accessToken->getValue();
        }else{
            return $this->failUnauthorized("找不到認證資訊，請傳入合法的 API Token");
        }

        $jwtAuth = Services::Auth();
        try {
            $result = $jwtAuth->verification($accessToken);
        } catch (\Throwable $th) {
            log_message('critical', $th->__toString());
            return $this->respond(["statusCode"=>"Auth006","msg"=>"驗證過程發生未知錯誤"],400);
        }
        //若驗證失敗則回傳失敗內容
        if($result["code"] != 200){
            return $this->respond([
                "statusCode"=>$result["statusCode"],
                "msg" => $result["msg"]
            ],$result["code"]);   
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
