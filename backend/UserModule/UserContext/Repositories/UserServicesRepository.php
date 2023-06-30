<?php

namespace UserModule\UserContext\Repositories;

use App\Anser\Services\Gitlab\GitlabUserService;
use \SDPMlab\Anser\Service\Action;
use UserModule\UserContext\Entities\User;

class UserServicesRepository
{

    /**
     * 新增 Git 使用者
     *
     * @param User $user
     * @param string $password 使用者實體
     * @return \SDPMlab\Anser\Service\Action 被建立的使用者資料庫主鍵或 Action 實體
     */
    public function createGitUser(
        User $user,
        string $password
    ): Action{
		$gitlabService = new GitlabUserService();
        $usersCreationAction = $gitlabService->createUser(
			$user->email,
			$user->firstName,
			$user->lastName,
			$password
		);
        return $usersCreationAction;
    }


}
