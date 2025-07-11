<?php 

/**
 * Check if the current user has an active membership.
 *
 * @param int|null $user_id the user ID to check. If null, checks the current logged-in user.
 * @return bool True if the user has an active membership, false otherwise.
 */
function hclm_is_membership_active( $user_id = null ) {
    if ( ! function_exists( 'pms_get_member_subscriptions' ) )
        return false;

    if ( is_null( $user_id ) )
        $user_id = get_current_user_id();

    if ( ! $user_id )
        return false;

    $subscriptions = pms_get_member_subscriptions( array( 'user_id' => $user_id ) );
    if ( empty( $subscriptions ) )
        return false;

    $subscription = $subscriptions[0];
    $now = time();

    $has_valid_expiration = !empty( $subscription->expiration_date );
    $expiration_timestamp = $has_valid_expiration ? strtotime( $subscription->expiration_date ) : null;
    $has_auto_renew = !empty( $subscription->billing_next_payment );

    $is_active = in_array( $subscription->status, array( 'active', 'canceled' ) )
        && ( $has_valid_expiration ? $expiration_timestamp >= $now : $has_auto_renew );

    return $is_active;
}
