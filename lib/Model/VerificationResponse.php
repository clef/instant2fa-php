<?php

namespace Instant2FA\Model;

class VerificationResponse {
    public $id;
    public $distinct_id;
    public $status;

    public function __construct(array $options) {
        if (isset($options['id'])) {
            $this->id = $options['id'];
        }
        if (isset($options['distinct_id'])) {
            $this->distinct_id = $options['distinct_id'];
        }
        if (isset($options['status'])) {
            $this->status = $options['status'];
        }
    }
}
