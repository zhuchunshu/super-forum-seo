<?php

namespace App\Plugins\Seo\src\Task;

use Hyperf\Crontab\Annotation\Crontab;

#[Crontab(name:"baiduSubmit",rule:"0 * * * *",callback:"handler",memo:"百度推送")]
class BaiduSubmit
{
	public function handler(){
		var_dump(http('raw')->get(url("/seo/submit"))->getBody());
	}
}