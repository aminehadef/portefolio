<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project")
     */
    public function index()
    {
        $repoProject = $this->getDoctrine()->getRepository(Project::class);
        $projects = $repoProject->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    /**
     * @Route("/project/show/{id}", name="show_project")
     */
    public function showProject($id){

        $repoProject = $this->getDoctrine()->getRepository(Project::class);
        $project = $repoProject->find($id);

        return $this->render('project/show.html.twig',[
            'project' => $project,
        ]);
    }
}
