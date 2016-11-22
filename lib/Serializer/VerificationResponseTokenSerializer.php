<?php 

namespace Instant2FA\Serializer;

use Tobscure\JsonApi\AbstractSerializer;

class VerificationResponseTokenSerializer extends AbstractSerializer {
    protected $type = 'verification-response-tokens';

    public function getAttributes($token, array $fields = null) {
        return [
            'distinct_id' => $token->distinct_id,
            'status' => $token->status
        ];
    }
}
