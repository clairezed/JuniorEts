<?php

namespace Junior\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indemnites
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Junior\EtudiantBundle\Entity\IndemnitesRepository")
 */
class Indemnites
{
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Junior\EtudiantBundle\Entity\Etudiant", inversedBy="indemnites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiant;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Junior\EtudiantBundle\Entity\Etude", inversedBy="indemnites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etude;
    
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbJours", type="integer")
     */
    private $nbJours;

    /**
     * @var float
     *
     * @ORM\Column(name="retenue", type="float")
     */
    private $retenue;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbJours
     *
     * @param integer $nbJours
     * @return Indemnites
     */
    public function setNbJours($nbJours)
    {
        $this->nbJours = $nbJours;
    
        return $this;
    }

    /**
     * Get nbJours
     *
     * @return integer 
     */
    public function getNbJours()
    {
        return $this->nbJours;
    }

    /**
     * Set retenue
     *
     * @param float $retenue
     * @return Indemnites
     */
    public function setRetenue($retenue)
    {
        $this->retenue = $retenue;
    
        return $this;
    }

    /**
     * Get retenue
     *
     * @return float 
     */
    public function getRetenue()
    {
        return $this->retenue;
    }

    /**
     * Set etudiant
     *
     * @param \Junior\EtudiantBundle\Entity\Etudiant $etudiant
     * @return Indemnites
     */
    public function setEtudiant(\Junior\EtudiantBundle\Entity\Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;
    
        return $this;
    }

    /**
     * Get etudiant
     *
     * @return \Junior\EtudiantBundle\Entity\Etudiant 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set etude
     *
     * @param \Junior\EtudiantBundle\Entity\Etude $etude
     * @return Indemnites
     */
    public function setEtude(\Junior\EtudiantBundle\Entity\Etude $etude)
    {
        $this->etude = $etude;
    
        return $this;
    }

    /**
     * Get etude
     *
     * @return \Junior\EtudiantBundle\Entity\Etude 
     */
    public function getEtude()
    {
        return $this->etude;
    }
}