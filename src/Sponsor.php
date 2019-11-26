<?php
namespace NitroPay;

use NitroPay\Sponsor\CoreException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha512;

class Sponsor
{
    protected $privateKey;
    protected $email;
    protected $avatar;
    protected $name;

    public function __construct(string $privateKey) {
        if (!$privateKey) {
            throw new CoreException('Missing private key');
        }

        $this->privateKey = $privateKey;
        $this->email = '';
        $this->avatar = '';
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setAvatar(string $avatar) {
        $this->avatar = $avatar;
    }

    public function setName(string $name) {
        $this->avatar = $avatar;
    }

    public function sign($siteId, $userId) {
        $signer = new Sha512();
        $time = time();

        if (!$this->name) {
            $this->name = $userId;
        }

        $token = (new Builder())->issuedBy((string) $siteId)
                                ->setSubject((string) $userId)
                                ->issuedAt($time)
                                ->canOnlyBeUsedAfter($time)
                                ->withClaim('name', $this->name)
                                ->withClaim('email', $this->email)
                                ->withClaim('avatar', $this->avatar)
                                ->getToken($signer, new Key($this->privateKey));

        // reset
        $this->name = '';
        $this->email = '';
        $this->avatar = '';

        return $token;
    }
}
