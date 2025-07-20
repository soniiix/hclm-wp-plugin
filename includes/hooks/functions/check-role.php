<?php

/**
 * Check if a user has one of the specified roles.
 *
 * @param int $user_id The ID of the user to check.
 * @param array $roles_to_check An array of role slugs to check against the user's roles.
 * @return bool True if the user has one of the specified roles, false otherwise.
 */
function hclm_check_role($user_id, $roles_to_check) {
    $user = get_userdata($user_id);
    if (!$user) return false;

    foreach ($roles_to_check as $role) {
        if (in_array($role, (array) $user->roles)) {
            return true;
        }
    }

    return false;
}
