<?php

use YOOtheme\Application;
use ZOOlanders\YOOessentials\Condition\Wordpress\Rule\User\UserGroupRule;

$app = Application::getInstance();

$app->load(__DIR__ . '/modules/*/bootstrap.php');

// load rules manually if autoload is not implemented
require_once __DIR__ . '/rules/UserGroup/UserGroupRule.php';

return [
  // declare rules
  'yooessentials-condition-rules' => [
    UserGroupRule::class => __DIR__ . '/rules/UserGroup/config/user-group.json',
  ]
];
