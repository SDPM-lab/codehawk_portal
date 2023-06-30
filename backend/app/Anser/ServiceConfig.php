<?php

namespace App\Anser;

use SDPMlab\Anser\Service\ServiceList;

//新服務設定
ServiceList::addLocalService("gitlab_service","gitlab.sdpmlab.org","443",true);
ServiceList::addLocalService("wekan_service","wekan.sdpmlab.org","443",true);
