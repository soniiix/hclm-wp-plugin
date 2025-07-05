<?php 

/**
 * Get the future expiration date for a subscription renewal.
 * @param PMS_Member_Subscription $subscription The subscription data.
 * @return string|null The formatted renewal expiration date or null if not available.
 */
function get_renewal_expiration_date($subscription) {
    if (!$subscription || empty($subscription->expiration_date)) {
        return null;
    }

    $expiration_timestamp = strtotime($subscription->expiration_date);

    // Add one year to the expiration date
    $renew_timestamp = strtotime('+1 year', $expiration_timestamp);

    // Return the formatted date
    return date_i18n(get_option('date_format'), $renew_timestamp);
}

?>