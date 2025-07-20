<?php

/**
 * Check if the current user has one of the specified roles.
 *
 * @param array $roles_to_check Array of role slugs to check.
 * @return bool True if the current user has one of the roles, false otherwise.
 */
function hclm_current_user_has_role($roles_to_check) {
    $user = wp_get_current_user();
    if (!$user || empty($user->roles)) return false;

    foreach ($roles_to_check as $role) {
        if (in_array($role, (array) $user->roles)) {
            return true;
        }
    }

    return false;
}
