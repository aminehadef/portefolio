<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/user", name="user")
     */
    public function index()
    {
        $repoUsers = $this->getDoctrine()->getRepository(User::class);
        $users = $repoUsers->findAll();

        return $this->render('security/index.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/admin/user/new", name="new_user")
     */
    public function newUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_ADMIN']);

            $file = $request->files;
            $file = $file->get('user');
            if($file['filename'] != null){
                if($file['filename']->guessExtension() == "jpeg" || $file['filename']->guessExtension() == "png"){
                    $fileName = $this->generateUniqueFileName() . '.' . $file['filename']->guessExtension();
                    try {
                        $file['filename']->move('./images_avatre_admin', $fileName);
                    } catch (FileException $e) {
                        
                    }

                    $user->setFilename($fileName);
                }
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('security/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/admin/user/edit/{id}" , name="edit_user")
     */
    public function editUser($id,Request $request, ObjectManager $manager){

        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repoUser->find($id);
        
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $file = $request->files->get('edit_user');
            $file = $file['filename'];


            if ($file){
                if($file->guessExtension() == 'jpeg' || $file->guessExtension() == 'png'){
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    try {
                        $file->move('./images_avatre_admin', $fileName);
                    } catch (FileException $e) {
                        
                    }
                    
                    $filesystem = new Filesystem();
                    try {
                        $filesystem->remove(['./images_avatre_admin/'.$user->getFilename()]);
                        $filesystem->remove(['./media/cache/avatar/images_avatre_admin/'.$user->getFilename()]);
                    } catch (IOExceptionInterface $e) {
                        
                    }                    

                    $user->setFilename($fileName);
                }
            }
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('security/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}" , name="delet_user")
     */
    public function deleteUser($id, ObjectManager $manager){

        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repoUser->find($id);

        $filesystem = new Filesystem();
        try {
            $filesystem->remove(['./images_avatre_admin/'.$user->getFilename()]);
            $filesystem->remove(['./media/cache/avatar/images_avatre_admin/'.$user->getFilename()]);
        } catch (IOExceptionInterface $e) {
            
        }

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('user');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $AuthenticationUtils){

        $lastUserName = $AuthenticationUtils->getLastUsername();
        $error = $AuthenticationUtils->getLastAuthenticationError();
        
        return $this->render('security/login.html.twig',[
            'lastUserName' => $lastUserName,
            'error' => $error,
        ]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
