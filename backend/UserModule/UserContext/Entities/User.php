<?php

namespace UserModule\UserContext\Entities;

class User
{
    public ?int $key;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $email;
    public ?string $img;
    public ?string $createdAt;
    public ?string $updatedAt;

    /**
     * 驗證是否通過
     */
    public ?bool $validation;

    /**
     * @var array<string,string>|null 驗證失敗訊息，Key為屬性名稱、Value為錯誤提示內容
     */
    public ?array $validationMessages;

    /**
     * 驗證規則
     */
    protected array $rules = [
        'email' => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'valid_email' => '帳號必須是合法的 Email 形式。'
            ]
        ]
    ];

    /**
     * 初始化使用者實體
     *
     * @param string|null $firstName 名
     * @param string|null $lastName 姓
     * @param string|null $email 信箱
     * @param string|null $password 密碼
     * @param string|null $rePassword 再次驗證密碼
     * @param integer|null $key 資料庫主鍵
     * @param string|null $img 大頭貼檔案名稱
     * @param string|null $createdAt 創建於
     * @param string|null $updatedAt 最後更新於
     */
    public function __construct(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?int $key = null,
        ?string $img = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ){
        //初始化
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->key = $key;
        $this->img = $img;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        //驗證
        $valid = \App\Helpers\ContextEntityValidation::validationData($this->rules,(array)$this);
        $this->validation = $valid["validation"];
        $this->validationMessages = $valid["errors"];
    }

}