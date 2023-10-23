<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    "enums" => [],
    "messages" => [
        "payment_list_found_successfully" => "Payment list found successfully",
        "payment_successfuly_created" => "Payment successfuly created",
        "payment_successfuly_found" => "Payment successfuly found",
        "the_payment_was_successfully_rejected" => "The payment was successfully rejected",
        "the_payment_was_successfully_approved" => "The payment was successfully approved"
    ],
    "validations" => [],
    "errors" => [
        "you_can_only_decline_pending_payments" => "You can only decline pending payments",
        "this_payment_has_already_been_used" => "This payment has already been used",
        "you_have_a_similar_payment_in_the_system" => "You have made a similar payment :time and it is not possible to register again."

    ],
];
