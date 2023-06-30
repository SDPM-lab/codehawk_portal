<?php
namespace UserModule\App\Entites;

use CodeIgniter\Entity;

class UserEntity extends Entity
{

    protected $casts = [
        'key' => 'integer'
    ];

    public function setPassword(string $pass)
    {
        $this->attributes['password'] = sha1($pass);
        return $this;
    }

}