<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Mail
{
    /**
     *@var string|null
     *@Assert\NotBlank(message="votre non est obligatoir")
     *@Assert\Length(
     *    max=30,
     *    maxMessage="votre prenom et trop long"
     * )
     */
    private $firstname;

    /**
     *@var string|null
     *@Assert\NotBlank(message="votre prenon est obligatoir")
     *@Assert\Length(
     *    max=30,
     *    maxMessage="votre nom et trop long"
     * )
     */
    private $lastname;

    /**
     *@var string|null
     *@Assert\NotBlank(message="votre email est obligatoir")
     *@Assert\Email()
     */
    private $email;

    /**
     *@var string|null
     *@Assert\NotBlank(message="votre telephone est obligatoir")
     *@Assert\Regex(
     *  pattern="/[0-9]{10}/",
     * message = "numero de telephone non valide"
     *)
     */
    private $phon;

    /**
     *@var string|null
     *@Assert\NotBlank(message="le message est obligatoir")
     *@Assert\Length(
     * min=10,
     * minMessage="ce champ est obligatoir minimum 10 caractairs"
     *)
     */
    private $message;

    /**
     * Get *@var string|null
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set *@var string|null
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get *@var string|null
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set *@var string|null
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get *@var string|null
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set *@var string|null
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get *@var string|null
     */ 
    public function getPhon()
    {
        return $this->phon;
    }

    /**
     * Set *@var string|null
     *
     * @return  self
     */ 
    public function setPhon($phon)
    {
        $this->phon = $phon;

        return $this;
    }

    /**
     * Get *@var string|null
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set *@var string|null
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}