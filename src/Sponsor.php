<?php
namespace NitroPay;

use NitroPay\Sponsor\CoreException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha512;

class Sponsor
{
    protected $privateKey;

    public function __construct(string $privateKey) {
        if (!$privateKey) {
            throw new CoreException('Missing private key');
        }

        $this->privateKey = $privateKey;
    }

    public function sign($siteId, $userId) {
        $signer = new Sha512();
        $time = time();

        $token = (new Builder())->issuedBy((string) $siteId)
                                ->setSubject((string) $userId)
                                ->issuedAt($time)
                                ->canOnlyBeUsedAfter($time)
                                ->getToken($signer, new Key($this->privateKey));

        return $token;
    }
}
