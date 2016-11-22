<?php

namespace Instant2FA;

use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use CloudCreativity\JsonApi\Decoders\DocumentDecoder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Instant2FA\Model\UserAccessToken;
use Instant2FA\Model\VerificationRequest;
use Instant2FA\Model\VerificationResponseToken;
use Instant2FA\Model\VerificationResponse;

use Instant2FA\Serializer\UserAccessTokenSerializer;
use Instant2FA\Serializer\VerificationRequestSerializer;
use Instant2FA\Serializer\VerificationResponseSerializer;

use Instant2FA\Error\APIException;
use Instant2FA\Error\MFANotEnabled;
use Instant2FA\Error\VerificationFailedException;
use Instant2FA\Error\VerificationMismatchException;


class Instant2FA {
    const DEFAULT_API_BASE = 'https://api.instant2fa.com/';

    protected $api_base;
    protected $access_key;
    protected $access_secret;

    protected $client;
    protected $default_request_options;
    protected $json_api_parser;

    public function __construct(array $config) {
        if (isset($config['access_key'])) {
            $this->access_key = $config['access_key'];
        } else {
            throw new APIException('You must provide an access_key parameter');
        }

        if (isset($config['access_secret'])) {
            $this->access_secret = $config['access_secret'];
        } else {
            throw new APIException('You must provide an access_secret parameter');
        }

        if (isset($config['api_base'])) {
            $this->api_base = $config['api_base'];
        } else {
            $this->api_base = Instant2FA::DEFAULT_API_BASE;
        }

        $this->default_request_options = [
            'auth' => [$this->access_key, $this->access_secret],
            'headers' => ['Content-Type' => 'application/vnd.api+json']
        ];
        $this->client = new Client(['base_uri' => $this->api_base]);
        $this->decoder = new DocumentDecoder();
    }

    public function create_settings($distinct_id) {
        $this->assertNotEmpty($distinct_id, 'distinct_id');

        $token = new UserAccessToken(['distinct_id' => $distinct_id]);
        $resource = new Resource($token, new UserAccessTokenSerializer);
        $document = new Document($resource);
        try {
            $response = $this->client->request(
                'POST',
                'user-access-tokens/',
                array_merge($this->default_request_options, ['body' => json_encode($document)])
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $error_body = $response->getBody();
                $doc = $this->decoder->decode($error_body);
                throw new APIException($doc->errors);
            }
        }

        $body = $response->getBody();
        $response_document = $this->decoder->decode($body);
        $token = new UserAccessToken((array) $response_document->data->attributes);
        return $token->hosted_page_url;
    }

    public function create_verification($distinct_id) {
        $this->assertNotEmpty($distinct_id, 'distinct_id');

        $request = new VerificationRequest(['distinct_id' => $distinct_id]);
        $resource = new Resource($request, new VerificationRequestSerializer);
        $document = new Document($resource);
        try {
            $response = $this->client->request(
                'POST',
                'verification-requests/',
                array_merge($this->default_request_options, ['body' => json_encode($document)])
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                if ($response->getStatusCode() == 422) {
                    throw new MFANotEnabled("The user does not have 2FA enabled.");
                }
                $error_body = $response->getBody();
                $doc = $this->decoder->decode($error_body);
                throw new APIException($doc->errors);
            }
        }
        $body = $response->getBody();
        $response_document = $this->decoder->decode($body);
        $request = new VerificationRequest((array) $response_document->data->attributes);
        return $request->hosted_page_url;
    }

    private function assertNotEmpty($str, $name = 'distinct_id') {
        if ($str == NULL) {
            throw new APIException([
                'title' => 'BadRequest',
                'detail' => 'The \'' . $name . '\' parameter cannot be null or the empty string',
                'status' => 400
            ]);
        }
    }

    public function confirm_verification($distinct_id, $token) {
        $this->assertNotEmpty($distinct_id, 'distinct_id');
        $this->assertNotEmpty($token, 'token');

        try {
            $response = $this->client->request(
                'GET',
                'verification-response-tokens/' . $token,
                $this->default_request_options
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $error_body = $response->getBody();
                $doc = $this->decoder->decode($error_body);
                throw new APIException($doc->errors);
            }
        }
        $body = $response->getBody();
        $response_document = $this->decoder->decode($body);
        $verification_response = new VerificationResponse((array) $response_document->data->attributes);

        if ($distinct_id != $verification_response->distinct_id) {
            throw new VerificationMismatchException("The distinctID passed back from the request didn't match the one passed into this function. Are you passing in the right value for distinctID?");
        }
        if ($verification_response->status != 'succeeded') {
            throw new VerificationFailedException($verification_response->status);
        }

        return $verification_response->status == 'succeeded';
    }
}


