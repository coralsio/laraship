<?php

namespace Corals\Media\Classes;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CoralsPathGenerator implements PathGenerator
{
    /*
  * Get the path for the given media, relative to the root storage path.
  */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    /*
 * Get the path for conversions of the given media, relative to the root storage path.
 */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive/';
    }

    /*
     * Get a (unique) base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $root = $media->getCustomProperty('root', 'user_' . optional(user())->hashed_id);
        $key = $media->getCustomProperty('key', $media->getKey());

        return $root . '/' . $key;
    }
}