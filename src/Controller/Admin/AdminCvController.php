<?php

namespace App\Controller\Admin;

use App\Entity\Cv;
use App\Form\CvType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminCvController extends AbstractController
{
    /**
     * @Route("/admin/cv/new", name="new_cv")
     */
    public function newCv(Request $request, ObjectManager $manager)
    {
        $cv = new Cv();

        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $cv->getCv();
            $fileName = 'cv_amine_hadef.'.$file->guessExtension();
            $cv->setCv($fileName);
            try {
                $file->move('./cv', $fileName);
            } catch (FileException $e) {
                
            }
        }
        return $this->render('Admin/admin_cv/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/cv/show",name="admin_show")
     */
    public function showCv(){
        
        return $this->render('Admin/admin_cv/show.html.twig');
    }
}