<?php


namespace App\Controller;


use App\CatGenerator\CatGeneratorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CatGeneratorController extends AbstractController
{
    /**
     * @Route("/sync")
     * @Template("cat_generator/index.html.twig")
     *
     * @param CatGeneratorService $catGeneratorService
     * @return array
     */
    public function indexSyncAction(CatGeneratorService $catGeneratorService)
    {
        $imageUrl = $catGeneratorService->fetchCatUrls(100);
        return [
            "image_url" => $imageUrl
        ];
    }

    /**
     * @Route("/async")
     * @Template("cat_generator/index.html.twig")
     *
     * @param CatGeneratorService $catGeneratorService
     * @return array
     */
    public function indexAsyncAction(CatGeneratorService $catGeneratorService)
    {
        $imageUrl = $catGeneratorService->fetchCatUrlsAsync(100);

        return [
            "image_url" => $imageUrl
        ];
    }
}