<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\KernelInterface;

class AssetController extends AbstractController
{
    /**
     * @Route("/uploads/files/{file}", name="asset_files")
     */
    public function getFile(string $file, KernelInterface $kernel)
    {
        $path = $kernel->getProjectDir();
        return new BinaryFileResponse($path. '/private_assets/uploads/files/'. $file);
    }
    
    /**
     * @Route("/uploads/images/{file}", name="asset_images")
     */
    public function getImage(string $file, KernelInterface $kernel)
    {
        $path = $kernel->getProjectDir();
        return new BinaryFileResponse($path. '/private_assets/uploads/images/'. $file);
    }
}
