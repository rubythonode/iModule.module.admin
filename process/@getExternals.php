<?php
/**
 * 이 파일은 iModule 관리자모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 서버에 존재하는 외부페이지를 불러온다.
 *
 * @file /modules/admin/process/@getExternals.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0.160903
 *
 * @post string $domain 사이트도메인
 * @post string $language 사이트언어셋
 * @return object $results
 */
if (defined('__IM__') == false) exit;

$domain = Request('domain');
$language = Request('language');

/**
 * 공용 외부파일폴더를 탐색한다.
 */
$lists = array();
$externalsPath = @opendir(__IM_PATH__.'/externals');
while ($external = @readdir($externalsPath)) {
	if ($external != '.' && $external != '..' && is_file(__IM_PATH__.'/externals/'.$external) == true) {
		$lists[] = array(
			'path'=>__IM_DIR__.'/externals/'.$external
		);
	}
}
@closedir($externalsPath);

/**
 * 사이트템플릿의 외부파일폴더를 탐색한다.
 */
$templetsPath = @opendir(__IM_PATH__.'/templets');
while ($templet = @readdir($templetsPath)) {
	if ($templet != '.' && $templet != '..' && is_dir(__IM_PATH__.'/templets/'.$templet) == true) {
		$externalsPath = @opendir(__IM_PATH__.'/templets/'.$templet.'/externals');
		while ($external = @readdir($externalsPath)) {
			if ($external != '.' && $external != '..' && is_file(__IM_PATH__.'/templets/'.$templet.'/externals/'.$external) == true) {
				$lists[] = array(
					'path'=>__IM_DIR__.'/templets/'.$templet.'/externals/'.$external
				);
			}
		}
		@closedir($externalsPath);
	}
}
@closedir($templetsPath);

/**
 * 사이트 탬플릿을 사용하는 모듈에서 탐색한다.
 */
$modules = $this->IM->db()->select($this->IM->getModule()->getTable('module'))->where('is_templet','TRUE')->get();
for ($i=0, $loop=count($modules);$i<$loop;$i++) {
	$externalsPath = @opendir($this->IM->getModule()->getPath($modules[$i]->module).'/templets/externals');
	while ($external = @readdir($externalsPath)) {
		if ($external != '.' && $external != '..' && is_file($this->IM->getModule()->getPath($modules[$i]->module).'/templets/externals/'.$external) == true) {
			$lists[] = array(
				'path'=>$this->IM->getModule()->getDir($modules[$i]->module).'/templets/externals/'.$external
			);
		}
	}
}

$results->success = true;
$results->lists = $lists;
$results->total = count($lists);
?>