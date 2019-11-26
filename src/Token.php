<?php
namespace Sponsor;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key;
use Sponsor\Exception\CoreException;

class Token
{
    private $bldr;
    private $key;
    private $signer;

    public function __construct(string $key)
    {
        if (!$key) {
            throw new CoreException('Missing private key');
        }

        $this->bldr = new Builder();
        $this->key = new Key($key);
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

        return $token->getToken($this->signer, $this->key);
    }
}
