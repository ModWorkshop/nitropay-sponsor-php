<?php
namespace NitroPay;

use NitroPay\Sponsor\CoreException;
use ReallySimpleJWT\Build;
use ReallySimpleJWT\Validate;
use ReallySimpleJWT\Encode;

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
        $build = new Build('JWT', new Validate(), new Encode());

        $token = $build->setContentType('JWT')
            ->setHeaderClaim('alg', 'HS512')
            ->setHeaderClaim('typ', 'JWT')
            ->setSecret($this->privateKey)
            ->setIssuer((string) $siteId)
            ->setSubject((string) $userId)
            ->setIssuedAt(time())
            ->build();

        return $token;
    }
}
