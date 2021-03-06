<?php
/**
 * 이 파일은 iModule 관리자모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 특정모듈의 전체 컨텍스트 목록을 가져온다.
 *
 * @file /modules/admin/process/@getModuleContexts.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0.160903
 *
 * @post string $target 모듈명
 * @return object $results
 */
if (defined('__IM__') == false) exit;

$module = Request('target');
$contexts = $this->Module->getContexts($module);

$results->success = true;
$results->lists = $contexts;
$results->count = count($contexts);
?>