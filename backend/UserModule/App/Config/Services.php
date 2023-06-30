<?php

namespace UserModule\App\Config;

use CodeIgniter\Config\Services as CoreServices ;

use UserModule\App\Services\Auth;

class Services extends CoreServices
{
	
	/**
	 * JWT 驗證類別
	 *
	 * @param boolean $getShared
	 * @return \UserModule\App\Services\Auth
	 */
	public static function auth(bool $getShared = true): Auth{
		if($getShared){
			return static::getSharedInstance("auth");
		}
		return new Auth();
	}
}
