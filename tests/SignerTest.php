<?php
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;
use NitroPaySponsor\Exception\CoreException;
use NitroPaySponsor\Signer;

final class NitroPaySignerTest extends TestCase
{
    public function test(): void
    {
        $this->assertInstanceOf(
            Signer::class,
            new Signer('foobar')
        );
    }

    public function testSignMissingSiteIdThrowsException()
    {
        $this->expectException(CoreException::class);

        $signer = new Signer('foobar');
        $signer->sign([]);
    }

    public function testSignMissingUserIdThrowsException()
    {
        $this->expectException(CoreException::class);

        $signer = new Signer('foobar');
        $signer->sign(['siteId' => '1']);
    }
}
