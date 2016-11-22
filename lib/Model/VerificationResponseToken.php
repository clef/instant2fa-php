<?php

namespace Instant2FA\Model;


class VerificationResponseToken {
    public $id;
    public $distinct_id;

    public function __construct(array $options) {
        if (isset($options['id'])) {
            $this->id = $options['id'];
        }
        if (isset($options['distinct_id'])) {
            $this->distinct_id = $options['distinct_id'];
        }
    }
}
