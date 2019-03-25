<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site")
     */
    public function index()
    {
        return $this->render('common/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/rules-sv", name="rules_sv")
     */
    public function rules()
    {
        return $this->render('common/rules_sv.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
}

