<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\EditContactType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function index(){

        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repoContact->findAll();

        return $this->render('Admin/admin_contact/index.html.twig',[
            'contacts' => $contacts,
        ]);
    }
    /**
     * @Route("/admin/contact/new", name="new_contact")
     */
    public function newContact(Request $request, ObjectManager $manager){
        
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $classBo = $request->request->get('contact');
            $classBo = $classBo['class'];
            $str = '';

            
            if(preg_match('/^<[a-z A-Z]+/', $classBo)){
                $str = preg_replace('/(<[a-zA-Z0-9]{1,})|(><\/[a-zA-Z0-9]{1,}>)|(class=)|([a-zA-Z0-9]+="[a-zA-Z0-9]+")/','',$classBo);
                $str = trim($str);
                $str = substr($str, 0, -1);
                $str = substr($str, 1);
            }
            else if(!preg_match('/^<[a-z A-Z]+/', $classBo)){
                $str = $classBo;
            }
            if(!\preg_match('/fa-10x/',$str)){
                $str = $str . ' fa-10x';
            }
            if (!preg_match('#icone#', $str)) {
                $str = $str . ' icone';
            }

            $contact->setClass($str);

            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('admin_contact');
        }

        return $this->render('Admin/admin_contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/contact/edit/{id}", name="edit_contact", requirements={"id"="\d+"})
    */
    public function editContact($id,Request $request, ObjectManager $manager){

        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contact = $repoContact->find($id);

        $form = $this->createForm(EditContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('admin_contact');
        }

        return $this->render('Admin/admin_contact/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/contact/delet/{id}", name="delete_contact", requirements={"id"="\d+"})
     */
    public function deleteContact($id, ObjectManager $manager, Request $request){
        
        if($this->isCsrfTokenValid('delete' . $id , $request->get('_token'))){
            $repoContact =  $this->getDoctrine()->getRepository(Contact::class);
            $contact = $repoContact->find($id);
    
            $manager->remove($contact);
            $manager->flush();
        }
        return $this->redirectToRoute('admin_contact');
    }
}