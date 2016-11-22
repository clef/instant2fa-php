<?php

namespace Instant2FA\Error;

use Instant2FA\Error\Instant2FAException;

class APIException extends Instant2FAException {
    public $errors;

    public function __construct(array $errors) {
        parent::__construct("The request failed with errors: " . print_r($errors, true));
        $this->errors = $errors;
    }
}
