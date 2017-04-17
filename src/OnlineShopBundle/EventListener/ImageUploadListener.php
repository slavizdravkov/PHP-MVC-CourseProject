<?php


namespace OnlineShopBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Product) {
            return;
        }

        $file = $entity->getImageUrl();

        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName =  $this->uploader->upload($file);
        $entity->setImageUrl($fileName);
    }


}