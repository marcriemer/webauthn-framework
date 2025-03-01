<?php

declare(strict_types=1);

namespace Webauthn;

use ParagonIE\ConstantTime\Base64UrlSafe;
use Webauthn\Exception\InvalidDataException;
use function array_key_exists;
use function is_array;
use const JSON_THROW_ON_ERROR;

class PublicKeyCredentialUserEntity extends PublicKeyCredentialEntity
{
    public readonly string $id;

    public function __construct(
        string $name,
        string $id,
        public readonly string $displayName,
        ?string $icon = null
    ) {
        parent::__construct($name, $icon);
        mb_strlen($id, '8bit') <= 64 || throw InvalidDataException::create($id, 'User ID max length is 64 bytes');
        $this->id = $id;
    }

    public static function create(string $name, string $id, string $displayName, ?string $icon = null): self
    {
        return new self($name, $id, $displayName, $icon);
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public static function createFromString(string $data): self
    {
        $data = json_decode($data, true, flags: JSON_THROW_ON_ERROR);
        is_array($data) || throw InvalidDataException::create($data, 'Invalid data');

        return self::createFromArray($data);
    }

    /**
     * @param mixed[] $json
     */
    public static function createFromArray(array $json): self
    {
        array_key_exists('name', $json) || throw InvalidDataException::create(
            $json,
            'Invalid input. "name" is missing.'
        );
        array_key_exists('id', $json) || throw InvalidDataException::create($json, 'Invalid input. "id" is missing.');
        array_key_exists('displayName', $json) || throw InvalidDataException::create(
            $json,
            'Invalid input. "displayName" is missing.'
        );
        $id = Base64UrlSafe::decodeNoPadding($json['id']);

        return self::create($json['name'], $id, $json['displayName'], $json['icon'] ?? null);
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        $json = parent::jsonSerialize();
        $json['id'] = Base64UrlSafe::encodeUnpadded($this->id);
        $json['displayName'] = $this->displayName;

        return $json;
    }
}
