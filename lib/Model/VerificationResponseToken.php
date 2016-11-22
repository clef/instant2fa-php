<?php

namespace Instant2FA\Model;


class VerificationResponseToken {
    public $id;
    public $distinct_id;

    public function __construct(array $options) {
        $this->id = $options['id'];
        $this->distinct_id = $options['distinct_id'];
    }
}
