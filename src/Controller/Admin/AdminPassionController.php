<?php

namespace App\Controller\Admin;

use App\Entity\Passion;
use App\Form\PassionType;
use App\Entity\ImagePassion;
use App\Form\EditPassionType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminPassionController extends AbstractController
{
    /**
     * @Route("/admin/passion", name="admin_passion")
     */
    public function index()
    {
        $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
        $passions = $repoPassion->findAll();

        return $this->render('Admin/admin_passion/index.html.twig', [
            'passions' => $passions,
        ]);
    }
    /**
     * @Route("/admin/passion/new",name="new_passion")
     */
    public function newPassion(Request $request, ObjectManager $manager){

        $passion = new Passion();

        $form = $this->createForm(PassionType::class, $passion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $passion->setCreatedAt(new \DateTime('now'));

            $files = $request->files->get('passion');
            $files = $files['path'];

            $user = $this->getUser();
            $passion->setUser($user);

            for ($i=0; $i < \count($files); $i++){

                if($files[$i]->guessExtension() == "jpeg" || $files[$i]->guessExtension() == "png"){

                    $fileName = $this->generateUniqueFileName().'.'.$files[$i]->guessExtension();
                    try {
                        $files[$i]->move('./images_passion', $fileName);
                    } catch (FileException $e) {
                        
                    }
                   

                    $image = new ImagePassion();
                    $image->setPath($fileName)
                          ->setPassion($passion)
                    ;

                    $manager->persist($image);
                }
            }

            $manager->persist($passion);
            $manager->flush();

            $this->addFlash(
                'massage',
                'passion crée avec succès :)'
            );

            return $this->redirectToRoute('admin_passion');
        }

        return $this->render('Admin/admin_passion/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/passion/edit/{id}", name="edit_passion", requirements={"id"="\d+"})
     */
    public function editPassion($id,Request $request, ObjectManager $manager){

        $userName = $this->getUser()->getUsername();

        $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
        $passion = $repoPassion->find($id);

        $form = $this->createForm(EditPassionType::class, $passion);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $files = $request->files->get('edit_passion');
            $files = $files['path'];

            if (count($files) > 0 ){

                for ($i=0; $i < \count($files); $i++){

                    if($files[$i]->guessExtension() == "jpeg" || $files[$i]->guessExtension() == "png"){

                        $fileName = $this->generateUniqueFileName().'.'.$files[$i]->guessExtension();
                        try {
                            $files[$i]->move('./images_passion', $fileName);
                        } catch (FileException $e) {
                            
                        }
                        

                        $image = new ImagePassion();
                        $image->setPath($fileName)
                              ->setPassion($passion);

                        $manager->persist($image);
                    } 
                }
            }

            $passion->setLastEditBy($userName);

            $manager->persist($passion);
            $manager->flush();

            $this->addFlash(
                'massage',
                'passion editer avec succès :)'
            );

            return $this->redirectToRoute('admin_passion');
        }

        return $this->render('Admin/admin_passion/edit.html.twig',[
            'form' => $form->createView(),
            'passion' => $passion,
        ]);
    }

    /**
     * @Route("/admin/passion/edit/{id_passion}/{id_image}", name="delete_image_passion")
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    public function delete_image_passion($id_passion, $id_image, ObjectManager $manager) : 
    Response
    {

        $repoImage = $this->getDoctrine()->getRepository(ImagePassion::class);
        $image = $repoImage->find($id_image);

        $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
        $passion = $repoPassion->find($id_passion);
        $passion->setLastEditBy($this->getUser()->getUsername());

        $filesystem = new Filesystem();
        try {
            $filesystem->remove(['./images_passion/'.$image->getPath()]);
            $filesystem->remove(['./media/cache/big/images_passion/'.$image->getPath()]);
            $filesystem->remove(['./media/cache/card/images_passion/'.$image->getPath()]);
        } catch (IOExceptionInterface $e) {
            
        }

        $manager->remove($image);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'cool',
        ], 200);
    }
    /**
     * @Route("/admin/passion/delete/{id}",name="delete_passion")
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    public function deletePassion($id, ObjectManager $manager, Request $request) : 
    Response
    {
        if($this->isCsrfTokenValid('delete' . $id , $request->get('_token'))){
            $repoPassion = $this->getDoctrine()->getRepository(Passion::class);
            $passion = $repoPassion->find($id);
    
            $repoImage = $this->getDoctrine()->getRepository(ImagePassion::class);
            $images = $repoImage->findBy(['passion' => $id]);
    
            for($i = 0; $i < count($images); $i++){
    
                $filesystem = new Filesystem();
                try {
                    $filesystem->remove(['./images_passion/'.$images[$i]->getPath()]);
                    $filesystem->remove(['./media/cache/big/images_passion/'.$images[$i]->getPath()]);
                    $filesystem->remove(['./media/cache/card/images_passion/'.$images[$i]->getPath()]); 
                } catch (IOExceptionInterface $e) {
                    
                }
    
                $manager->remove($images[$i]);
                $manager->flush();
            }
            $manager->remove($passion);
            $manager->flush();
        }
        return $this->json([
            'code' => 200,
            'message' => 'cool',
        ], 200);
    }
        
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}