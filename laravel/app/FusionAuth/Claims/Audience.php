<?php

declare(strict_types=1);

namespace App\FusionAuth\Claims;

use Tymon\JWTAuth\Claims\Audience as BaseAudienceClaim;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Audience extends BaseAudienceClaim
{
    private array $validValues;

    /**
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     */
    public function validatePayload(): bool
    {
        if (!$this->validate()) {
            throw new TokenInvalidException('Audience (aud) invalid');
        }

        return true;
    }

    private function validate(): bool
    {
        // Audience must be set
        $value = $this->getValue();
        if (empty($value)) {
            return false;
        }

        if (!isset($this->validValues)) {
            /** @var string[] $validAudiences */
            $this->validValues = (array) config('jwt.validators.aud');
        }

        // If we have specified valid values, we check if the current issue is present there
        if (empty($this->validValues)) {
            return true;
        }

        return \in_array(\strtolower($value), $this->validValues);
    }
}
