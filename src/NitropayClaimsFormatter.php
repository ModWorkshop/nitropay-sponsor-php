<?php

namespace NitroPaySponsor;

use DateTimeImmutable;
use Lcobucci\JWT\ClaimsFormatter;
use Lcobucci\JWT\Token\RegisteredClaims;

class NitropayClaimsFormatter implements ClaimsFormatter
{
    /** @inheritdoc */
    public function formatClaims(array $claims): array
    {
        foreach (RegisteredClaims::DATE_CLAIMS as $claim) {
            if (! array_key_exists($claim, $claims)) {
                continue;
            }

            $claims[$claim] = $this->convertDate($claims[$claim]);
        }

        return $claims;
    }

    /**
     * @param DateTimeImmutable $date
     * @return int
     */
    private function convertDate(DateTimeImmutable $date): int
    {
        return (int)$date->format('U');
    }
}