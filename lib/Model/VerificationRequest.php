<?php

namespace Instant2FA\Model;

class VerificationRequest {
    public $id;
    public $distinct_id;
    public $hosted_page_url;
    public $status;

    public function __construct(array $options) {
        $this->id = $options['id'];
        $this->distinct_id = $options['distinct_id'];
        $this->hosted_page_url = $options['hosted_page_url'];
        $this->status = $options['status'];
    }
}

