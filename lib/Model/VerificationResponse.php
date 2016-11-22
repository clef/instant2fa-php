<?php

namespace Instant2FA\Model;

class VerificationResponse {
    public $id;
    public $distinct_id;
    public $status;

    public function __construct(array $options) {
        $this->id = $options['id'];
        $this->distinct_id = $options['distinct_id'];
        $this->status = $options['status'];
    }
}
