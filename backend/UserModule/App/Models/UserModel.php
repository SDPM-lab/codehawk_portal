<?php namespace UserModule\App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'key';

    protected $returnType = 'UserModule\App\Entites\UserEntity';

    protected $allowedFields = ['email', 'first_name', 'last_name'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
