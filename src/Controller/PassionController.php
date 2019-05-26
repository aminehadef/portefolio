<?php

namespace App\Controller;

use App\Entity\Passion;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PassionController extends AbstractController
{
    /**
     * @Route("/passion", name="passion")
     */
    public function index()
    {
        $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
        $passions = $repoPassion->findAll();

        return $this->render('passion/index.html.twig', [
            'passions' => $passions,
        ]);
    }
    /**
     * @Route("/passion/show/{id}", name="show_passion")
     */
    public function showPassion($id){

        $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
        $passion = $repoPassion->find($id);

        return $this->render('passion/show.html.twig',[
            'passion' => $passion,
        ]);
    }
}
