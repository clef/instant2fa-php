<?php

namespace Instant2FA\Serializer;

use Tobscure\JsonApi\AbstractSerializer;

class VerificationRequestSerializer extends AbstractSerializer {
    protected $type = 'verification-requests';

    public function getAttributes($request, array $fields = null) {
        return [
            'distinct_id' => $request->distinct_id,
            'hosted_page_url' => $request->hosted_page_url,
            'status' => $request->status
        ];
    }
}
