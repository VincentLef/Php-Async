<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CatGeneratorController extends AbstractController
{
    /**
     * @Route('/', name="cat_generator_index")
     * @Template()
     */
    public function indexAction()
    {

    }
}