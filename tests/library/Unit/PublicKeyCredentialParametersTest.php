<?php

declare(strict_types=1);

namespace Webauthn\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webauthn\PublicKeyCredentialParameters;
use const JSON_THROW_ON_ERROR;

/**
 * @internal
 */
final class PublicKeyCredentialParametersTest extends TestCase
{
    #[Test]
    public function anPublicKeyCredentialParametersCanBeCreatedAndValueAccessed(): void
    {
        $parameters = PublicKeyCredentialParameters::create('type', 100);

        static::assertSame('type', $parameters->type);
        static::assertSame(100, $parameters->alg);
        static::assertSame('{"type":"type","alg":100}', json_encode($parameters, JSON_THROW_ON_ERROR));

        $data = PublicKeyCredentialParameters::createFromString('{"type":"type","alg":100}');
        static::assertSame('type', $data->type);
        static::assertSame(100, $data->alg);
        static::assertSame('{"type":"type","alg":100}', json_encode($data, JSON_THROW_ON_ERROR));
    }
}
