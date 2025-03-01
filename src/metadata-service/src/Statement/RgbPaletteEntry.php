<?php

declare(strict_types=1);

namespace Webauthn\MetadataService\Statement;

use JsonSerializable;
use Webauthn\MetadataService\Exception\MetadataStatementLoadingException;
use function array_key_exists;
use function is_int;

class RgbPaletteEntry implements JsonSerializable
{
    public function __construct(
        public readonly int $r,
        public readonly int $g,
        public readonly int $b,
    ) {
        ($r >= 0 && $r <= 255) || throw MetadataStatementLoadingException::create('The key "r" is invalid');
        ($g >= 0 && $g <= 255) || throw MetadataStatementLoadingException::create('The key "g" is invalid');
        ($b >= 0 && $b <= 255) || throw MetadataStatementLoadingException::create('The key "b" is invalid');
    }

    public static function create(int $r, int $g, int $b): self
    {
        return new self($r, $g, $b);
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getR(): int
    {
        return $this->r;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getG(): int
    {
        return $this->g;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getB(): int
    {
        return $this->b;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function createFromArray(array $data): self
    {
        foreach (['r', 'g', 'b'] as $key) {
            array_key_exists($key, $data) || throw MetadataStatementLoadingException::create(sprintf(
                'The key "%s" is missing',
                $key
            ));
            is_int($data[$key]) || throw MetadataStatementLoadingException::create(
                sprintf('The key "%s" is invalid', $key)
            );
        }

        return self::create($data['r'], $data['g'], $data['b']);
    }

    /**
     * @return array<string, int>
     */
    public function jsonSerialize(): array
    {
        return [
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b,
        ];
    }
}
