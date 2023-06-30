<?php

namespace UserModule\UserContext\Tests;

use CodeIgniter\Test\CIUnitTestCase;
use UserModule\UserContext\Entities\User;
use CodeIgniter\Test\Fabricator;
use UserModule\App\Models\UserModel;

class UserEntityTest extends CIUnitTestCase
{
    public function testNewUserEntity()
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'firstName'  => 'firstName',
            'lastName'  => 'lastName',
            'email'  => 'email'
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $user = new User($fakeData["firstName"], $fakeData["lastName"], $fakeData["email"]);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->firstName, $fakeData["firstName"]);
        $this->assertEquals($user->lastName, $fakeData["lastName"]);
        $this->assertEquals($user->email, $fakeData["email"]);
        $this->assertTrue($user->validation);
    }

    public function testNewUserEntityError()
    {
        $fabrkcator = new Fabricator(UserModel::class, [
            'firstName'  => 'firstName',
            'lastName'  => 'lastName',
            'email'  => 'email'
        ], 'zh_TW');
        $fakeData = $fabrkcator->makeArray();
        $user = new User($fakeData["firstName"], $fakeData["lastName"], 'dfsdiojwioef');
        $this->assertNotTrue($user->validation);
        $this->assertIsArray($user->validationMessages);
        $this->assertIsString($user->validationMessages["email"]);
    }

}
