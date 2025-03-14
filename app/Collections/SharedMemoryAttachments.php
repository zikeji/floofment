<?php

namespace App\Collections;

use App\Objects\SharedMemoryAttachment;
use Illuminate\Support\Collection;

/**
 * @extends Collection<SharedMemoryAttachment>
 */
class SharedMemoryAttachments extends Collection
{
    public function __construct(array $attachments)
    {
        foreach ($attachments as &$attachment) {
            if (is_array($attachment)) {
                $attachment = SharedMemoryAttachment::fromArray($attachment);
            }
        }
        parent::__construct($attachments);
    }

    public function jsonSerialize(): array
    {
        return $this->map(fn (SharedMemoryAttachment $attachment) => $attachment->jsonSerialize())->all();
    }
}
