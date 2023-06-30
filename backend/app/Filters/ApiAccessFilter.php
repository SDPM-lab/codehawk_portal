<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAccessFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
       $response = \CodeIgniter\Config\Services::response();
       $response->setHeader("Access-Control-Allow-Origin","*");
       $response->setHeader("Access-Control-Allow-Credentials","true");
       $response->setHeader("Access-Control-Allow-Headers","Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, X-Access-Token, X-Refresh-Token, X-Oidc-Code");
       if(strcasecmp($request->getMethod(),"options") == 0){
           $response->setHeader("Access-Control-Allow-Methods","DELETE, PUT, POST, GET, OPTIONS");
       }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

}