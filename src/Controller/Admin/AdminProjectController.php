<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Entity\ImageProject;
use App\Form\EditProjectType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminProjectController extends AbstractController
{
    /**
     * @Route("/admin/project", name="admin_project")
     */
    public function index()
    {
        $repoProject = $this->getDoctrine()->getRepository(Project::class);
        $projects = $repoProject->findAll();

        return $this->render('Admin/admin_project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    /**
     * @Route("/admin/project/new", name="new_project")
     */
    public function newProject(Request $request, ObjectManager $manager){

        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $project->setCratedAt(new \DateTime('now'));

            $files = $request->files->get('project');
            $files = $files['path'];

            $user = $this->getUser();
            $project->setUser($user);

            for ($i=0; $i < \count($files); $i++){

                if($files[$i]->guessExtension() == "jpeg" || $files[$i]->guessExtension() == "png"){

                    $fileName = $this->generateUniqueFileName().'.'.$files[$i]->guessExtension();
                    try {
                        $files[$i]->move('./images_projects', $fileName);
                    } catch (FileException $r) {
                        
                    }
                    
                    $image = new ImageProject();
                    $image->setPath($fileName)
                          ->setProject($project)
                    ;

                    $manager->persist($image);
                }
            }

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'massage',
                'projet crée avec succès :)'
            );

            return $this->redirectToRoute('admin_project');
        }

        return $this->render('Admin/admin_project/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/project/edit/{id}", name="edit_project", requirements={"id"="\d+"})
     */
    public function editProject($id,Request $request, ObjectManager $manager){

        $userName = $this->getUser()->getUsername();

        $repoProject = $this->getDoctrine()->getRepository(Project::class);
        $project = $repoProject->find($id);
        
        $form = $this->createForm(EditProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $files = $request->files->get('edit_project');
            $files = $files['path'];

            if (count($files) > 0 ) {

                for ($i=0; $i < \count($files); $i++){

                    if($files[$i]->guessExtension() == "jpeg" || $files[$i]->guessExtension() == "png"){
                        
                        $fileName = $this->generateUniqueFileName().'.'.$files[$i]->guessExtension();
                        try {
                            $files[$i]->move('./images_projects', $fileName);
                        } catch (FileException $e) {
                            
                        }
                        
    
                        $image = new ImageProject();
                        $image->setPath($fileName)
                              ->setProject($project);

                        $manager->persist($image);
                    }
                }
            }

            $project->setLastEditBy($userName);

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'massage',
                'projet editer avec succès :)'
            );

            return $this->redirectToRoute('admin_project');
        }

        return $this->render('Admin/admin_project/edit.html.twig',[
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/project/edit/{id_project}/{id_image}", name="delete_image_project")
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    public function delete_image_project($id_project, $id_image, ObjectManager $manager) :
    Response
    {

        $repoImage = $this->getDoctrine()->getRepository(ImageProject::class);
        $image = $repoImage->find($id_image);

        $repoProject = $this->getDoctrine()->getRepository(Project::class);
        $project = $repoProject->find($id_project);
        $project->setLastEditBy($this->getUser()->getUsername());
        
        $filesystem = new Filesystem();
        try {
            $filesystem->remove(['./images_projects/'.$image->getPath()]);
            $filesystem->remove(['./media/cache/big/images_projects/'.$image->getPath()]);
            $filesystem->remove(['./media/cache/card/images_projects/'.$image->getPath()]); 
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
     * @Route("/admin/project/delete/{id}", name="delete_project")
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    public function deleteProject($id, ObjectManager $manager, Request $request) : 
    Response
    {
        if($this->isCsrfTokenValid('delete' . $id , $request->get('_token'))){
            $repoProject = $this->getDoctrine()->getRepository(Project::class);
            $project = $repoProject->find($id);
    
            $repoImage = $this->getDoctrine()->getRepository(ImageProject::class);
            $images = $repoImage->findBy(['project' => $id]);
    
            for($i = 0; $i < count($images); $i++){
    
                $filesystem = new Filesystem();
                try {
                    $filesystem->remove(['./images_projects/'.$images[$i]->getPath()]);
                    $filesystem->remove(['./media/cache/big/images_projects/'.$images[$i]->getPath()]);
                    $filesystem->remove(['./media/cache/card/images_projects/'.$images[$i]->getPath()]);
                } catch (IOExceptionInterface $e) {
                    //throw $th;
                }
    
                $manager->remove($images[$i]);
            }
    
            $manager->remove($project);
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