<?php
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;
use Sponsor\Exception\CoreException;
use Sponsor\Token;

final class SponsorTest extends TestCase
{
    public function test(): void
    {
        $this->assertInstanceOf(
            Token::class,
            new Token('foobar')
        );
    }

    public function testSignMissingSiteIdThrowsException()
    {
        $this->expectException(CoreException::class);

        $token = new Token('foobar');
        $token->sign([]);
    }

    public function testSignMissingUserIdThrowsException()
    {
        $this->expectException(CoreException::class);

        $token = new Token('foobar');
        $token->sign(['siteId' => '1']);
    }
}
