<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Currency Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    "enums" => [],
    "messages" => [
        "currency_successfully_created" => "Currency successfully created",
        "the_currency_has_been_activated_successfully" => "The currency has been activated successfully",
        "the_currency_has_been_deactivated_successfully" => "The currency has been deactivated successfully"


    ],
    "validations" => [
        "selected_currency_is_not_active" => "The selected currency isn't active"
    ],
    "errors" => [
        "currency_is_currently_active_and_cannot_be_reactivated" =>"The currency is currently active and cannot be reactivated",
        "currency_is_currently_inactive_and_cannot_be_reactivated" => "The currency is currently inactive and cannot be reactivated"
    ],
];
