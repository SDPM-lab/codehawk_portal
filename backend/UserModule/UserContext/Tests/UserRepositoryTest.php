<?php

namespace UserModule\UserContext\Tests;

use CodeIgniter\Test\CIDatabaseTestCase;
use UserModule\UserContext\Entities\User;
use UserModule\UserContext\Repositories\UserRepository;
use CodeIgniter\Test\Fabricator;
use UserModule\App\Models\UserModel;

class UserRepositoryTest extends CIDatabaseTestCase
{
    //資料庫相關設定
    protected $migrate = true;
    protected $namespace = 'UserModule';

    protected $userRepo;
    protected $userEmail;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    protected function createUser(): string
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'firstName'  => 'firstName',
            'lastName'  => 'lastName',
            'email'  => 'email',
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $user = new User($fakeData["firstName"], $fakeData["lastName"], $fakeData["email"]);
        $newUserKey = $this->userRepo->createUser($user);
        return $fakeData["email"];
    }

    public function testCreateUser()
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'firstName'  => 'firstName',
            'lastName'  => 'lastName',
            'email'  => 'email'
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $user = new User($fakeData["firstName"], $fakeData["lastName"], $fakeData["email"]);
        $newUserKey = $this->userRepo->createUser($user);
        $this->assertIsInt($newUserKey);
        //驗證實際資料是否寫入成功
        $userModel = new UserModel();
        $result = $userModel->find($newUserKey);
        $this->assertNotNull($result);
        $this->userEmail = $fakeData["email"];
    }

    public function testGetUserByEmail()
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'email'  => 'email',
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $userData = $this->userRepo->getUserByEmail($fakeData["email"]);
        $this->assertNull($userData);
        $email = $this->createUser();
        $userData = $this->userRepo->getUserByEmail($email);
        $this->assertInstanceOf(User::class,$userData);
    }

}
