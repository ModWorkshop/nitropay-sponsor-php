<?php
namespace NitroPaySponsor;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key;
use NitroPaySponsor\Exception\CoreException;

class Signer
{
    private $bldr;
    private $privateKey;
    private $signer;

    public function __construct(string $privateKey)
    {
        if (!$privateKey) {
            throw new CoreException('Missing private key');
        }

        $this->bldr = new Builder();
        $this->privateKey = new Key($privateKey);
        $this->signer = new Sha512();
    }

    public function sign($userinfo)
    {
        if (!array_key_exists('siteId', $userinfo)) {
            throw new CoreException('siteId is required');
        }

        if (!array_key_exists('userId', $userinfo)) {
            throw new CoreException('userId is required');
        }

        $time = time();

        $token = $this->bldr
            ->issuedBy((string) $userinfo['siteId'])
            ->setSubject((string) $userinfo['userId'])
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time);

        foreach (['name', 'email', 'avatar'] as $claim) {
            $value = array_key_exists($claim, $userinfo) ? $userinfo[$claim] : '';
            $token = $token->withClaim($claim, $value);
        }

        return $token->getToken($this->signer, $this->privateKey);
    }

    public function getUserSubscription($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: {$this->privateKey->getContent()}"
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
