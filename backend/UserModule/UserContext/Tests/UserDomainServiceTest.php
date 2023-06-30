<?php

namespace UserModule\UserContext\Tests;

use CodeIgniter\Test\CIDatabaseTestCase;
use CodeIgniter\Test\Fabricator;
use UserModule\UserContext\DomainServices\UserDomainService;
use UserModule\App\Models\UserModel;
use UserModule\UserContext\Entities\User;

class UserDomainServiceTest extends CIDatabaseTestCase
{
    //資料庫相關設定
    protected $migrate = true;
    protected $namespace = 'UserModule';
    // protected $migrateOnce = true;
    // protected $refresh = true;

    protected $userDomain;

    public function __construct()
    {
        parent::__construct();
        
        $this->userDomain = new UserDomainService();
    }

    public function testFindUser()
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'firstName'  => 'firstName',
            'lastName'  => 'lastName',
            'email'  => 'email'
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $user = $this->userDomain->findUser($fakeData["email"],$fakeData["firstName"],$fakeData["lastName"]);
        $this->assertInstanceOf(User::class,$user);
        
        $user2 = $this->userDomain->findUser($fakeData["email"],$fakeData["firstName"],$fakeData["lastName"]);
        $this->assertEquals($user->key,$user2->key);
    }

}
