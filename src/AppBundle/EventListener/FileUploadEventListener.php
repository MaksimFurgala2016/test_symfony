<?php
/**
 * Created by PhpStorm.
 * User: furga
 * Date: 01.07.2018
 * Time: 13:31
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;

class FileUploadEventListener
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
        // загрузка работает только для сущностей
        if (!$entity instanceof User) {
            return;
        }

        $file = $entity->getFile();

        // загружать только новые файлы
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setFile($fileName);
        }
    }

    public function postLoadFile(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User)
        {
            return;
        }
        if ($filename = $entity->getFile())
        {
            $entity->setFile(new File($this->uploader->getTargetDir() . '/' . $filename));
        }

    }
}