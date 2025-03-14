<?php

namespace App\Objects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

class SharedMemoryAttachment implements \JsonSerializable, Arrayable
{
    public readonly string $id;

    public readonly string $name;

    public readonly string $mimeType;

    public readonly string $extension;

    public readonly int $size;

    public function __construct(string $id, string $name, string $mimeType, string $extension, int $size)
    {
        $this->id = $id;
        $this->name = $name;
        $this->mimeType = $mimeType;
        $this->extension = $extension;
        $this->size = $size;
    }

    public function getUrl(): string
    {
        return Storage::disk('s3')->url("memory-attachments/{$this->id}.{$this->extension}");
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'size' => $this->size,
        ];
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            $data['mimeType'],
            $data['extension'],
            $data['size']
        );
    }
}
