<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\MailType;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repoContact->findAll();

        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            if ($this->recaptcha($secret, $_POST['g-recaptcha-response'])){

                $messageForMy = (new \Swift_Message('Email de : ' . $mail->getEmail()))
                ->setFrom('xxxx@gmail.com')
                ->setTo('xxxx@gmail.com')
                ->setBody(
                    "mail : " . $mail->getEmail()." <br/>" .
                    "telephon : " . $mail->getPhon() . " <br/>" .
                    "prenom : ". $mail->getFirstname() . " <br/>" .
                    "nom : " . $mail->getLastname() . " <br/>" .
                    "message : " . $mail->getMessage(),
                    'text/html'
                );
                $mailer->send($messageForMy);
                return $this->redirectToRoute('contact');
            }else{
                //$this->addFlash('success', 'vous n\'avez pas cocher la recapcha!');
                return $this->redirectToRoute('contact');
            }
        }

        

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form->createView(),
        ]);
    }
    private function recaptcha($secret,$code){
        if (empty($code)) {
            return false;
        }

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$code;

        if (function_exists('curl_version')) {
            $curl = \curl_init($url);
            \curl_setopt($curl, CURLOPT_HEADER, false);
            \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            \curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = \curl_exec($curl);
        }
        else{
            $response = \file_get_contents($url);
        }

        if (empty($response) || is_null($response)) {
            return false;
        }
        $json = \json_decode($response);
        return $json->success;
    }
}
