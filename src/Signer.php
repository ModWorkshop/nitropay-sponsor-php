<?php
namespace NitroPaySponsor;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use NitroPaySponsor\Exception\CoreException;

class Signer
{

    /**
     *
     * @var \Lcobucci\JWT\Configuration
     */
    private $signerConfig;

    public function __construct(string $privateKey)
    {
        if (!$privateKey) {
            throw new CoreException('Missing private key');
        }

        $this->signerConfig = Configuration::forSymmetricSigner(
            new Sha512(),
            InMemory::plainText($privateKey)
        );
    }

    public function sign($userinfo)
    {
        if (!array_key_exists('siteId', $userinfo)) {
            throw new CoreException('siteId is required');
        }

        if (!array_key_exists('userId', $userinfo)) {
            throw new CoreException('userId is required');
        }

        $now = new \DateTimeImmutable();
        $token = $this->signerConfig->builder(new NitropayClaimsFormatter())
            ->issuedBy((string) $userinfo['siteId'])
            ->relatedTo((string) $userinfo['userId'])
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now);

        foreach (['name', 'email', 'avatar'] as $claim) {
            $value = array_key_exists($claim, $userinfo) ? $userinfo[$claim] : '';
            $token = $token->withClaim($claim, $value);
        }

        $plainToken = $token->getToken($this->signerConfig->signer(), $this->signerConfig->signingKey());

        return $plainToken->toString();
    }

    public function getUserSubscription($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: {$this->signerConfig->signingKey()->contents()}"
        ]);
        curl_setopt($ch, CURLOPT_URL, "https://sponsor-api.nitropay.com/v1/users/{$userId}/subscription");

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if (!$result) {
            return false;
        } else {
            return json_decode($result);
        }
    }
}
