<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Intervention\Image\ImageManager;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Event\Event;

class ImageUploadSubscriber implements EventSubscriberInterface {

    public const MAX_HEIGHT = 480;
    
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager) {
        $this->imageManager = $imageManager;
    }

    public function onVichUploaderPreUpload(Event $event) {
        // Entity that has been updated
        $entity = $event->getObject();
        /** @var PropertyMapping */
        $mapping = $event->getMapping();
        /** @var string */
        $filePath = $mapping->getFile($entity);

        if (exif_imagetype($filePath)) {
            $image = $this->imageManager->make($filePath);
            
            if ($image->height() > self::MAX_HEIGHT) {
                $image->heighten(self::MAX_HEIGHT);
            }
            $image
                    ->orientate()
                    ->save($filePath);
        }
    }

    public static function getSubscribedEvents() {
        return [
            'vich_uploader.pre_upload' => 'onVichUploaderPreUpload',
        ];
    }

}
