<?php

namespace Instant2FA\Serializer;

use Tobscure\JsonApi\AbstractSerializer;

class UserAccessTokenSerializer extends AbstractSerializer {
    protected $type = 'user-access-tokens';

    public function getAttributes($token, array $fields = null) {
        return [
            'distinct_id' => $token->distinct_id,
            'hosted_page_url' => $token->hosted_page_url
        ];
    }
}

