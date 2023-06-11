<?php

namespace Corals\Utility\Traits\Gallery;

trait ModelHasGallery
{
    protected $galleryModelDefaultImage = '';

    public function getImageAttribute()
    {
        $defaultImage = $this->galleryModelDefaultImage;

        if (empty($defaultImage)) {
            $defaultImage = 'assets/corals/images/default_product_image.png';
        }
        $galleryMediaCollection = 'utility-gallery';

        if (property_exists($this, 'galleryMediaCollection')) {
            $galleryMediaCollection = $this->galleryMediaCollection;
        }

        $image = asset($defaultImage);

        $gallery = $this->getMedia($galleryMediaCollection);

        foreach ($gallery as $item) {
            if ($item->hasCustomProperty('featured')) {
                $image = $item->getFullUrl();
                break;
            }
        }

        return $image;
    }
}
