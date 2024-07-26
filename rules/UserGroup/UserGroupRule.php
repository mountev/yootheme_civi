<?php
/**
 * @package   Essentials YOOtheme Pro 2.2.15 build 0712.1334
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Wordpress\Rule\User;

class UserGroupRule extends UserRule
{
    public function resolve($props, $node): bool
    {
        if (!isset($props->groups)) {
          return true;
            throw new \RuntimeException('Not Valid Input');
        }

        $groupsUser = new \Groups_User($this->user->ID);
        // $groups = $groupsUser->get_groups();
        $groups = $groupsUser->get_group_ids();

        if (!$this->user->exists()) {
          return false;
        }

        if (!is_array($props->groups)) { $props->groups = []; }
        $selection = $props->groups;
        $strict = $props->strict ?? false;

        $missingGroups = array_diff($selection, $groups);

        return $strict ? count($missingGroups) === 0 : count($missingGroups) < count($selection);
    }

    public function fields(): array
    {
        return [
            'groups' => [
                'label' => 'Selection',
                'type' => 'select',
                'source' => true,
                'description' => 'The groups that the current user must be in. Use the shift or ctrl/cmd key to select multiple entries.',
                'attrs' => [
                    'multiple' => true,
                    'class' => 'uk-height-small uk-resize-vertical',
                ],
                'options' => $this->getUserGroups(),
                'enable' => '!guest',
            ],
            'strict' => [
                'text' => 'All selected groups must be met',
                'type' => 'checkbox',
                'enable' => '!guest',
            ]
        ];
    }

    protected function getUserGroups(): array
    {
        static $list = [];

        if (empty($list)) {
            require_once \ABSPATH . 'wp-admin/includes/user.php';

            $groups = \Groups_Group::get_groups();

            foreach ($groups as $group) {
                $list[$group->name] = $group->group_id;
            }
        }

        return $list;
    }
}
