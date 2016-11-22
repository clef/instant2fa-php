<?php

namespace Instant2FA\Model;

class VerificationRequest {
    public $id;
    public $distinct_id;
    public $hosted_page_url;
    public $status;

    public function __construct(array $options) {
        if (isset($options['id'])) {
            $this->id = $options['id'];
        }
        if (isset($options['distinct_id'])) {
            $this->distinct_id = $options['distinct_id'];
        }
        if (isset($options['hosted_page_url'])) {
            $this->hosted_page_url = $options['hosted_page_url'];
        }
        if (isset($options['status'])) {
            $this->status = $options['status'];
        }
    }
}

