<?php

namespace Junior\EtudiantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etude
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Junior\EtudiantBundle\Entity\EtudeRepository")
 */
class Etude
{
    
    /**
     * @ORM\OneToMany(targetEntity="Junior\EtudiantBundle\Entity\Indemnites", mappedBy="etude")
     */
    private $indemnites;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEtude", type="string", length=255)
     */
    private $nomEtude;

    /**
     * @var float
     *
     * @ORM\Column(name="prixJournee", type="float")
     */
    private $prixJournee;


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
     * Set numConvention
     *
     * @param integer $numConvention
     * @return Etude
     */
    public function setNumConvention($numConvention)
    {
        $this->numConvention = $numConvention;
    
        return $this;
    }

    /**
     * Get numConvention
     *
     * @return integer 
     */
    public function getNumConvention()
    {
        return $this->numConvention;
    }

    /**
     * Set nomEtude
     *
     * @param string $nomEtude
     * @return Etude
     */
    public function setNomEtude($nomEtude)
    {
        $this->nomEtude = $nomEtude;
    
        return $this;
    }

    /**
     * Get nomEtude
     *
     * @return string 
     */
    public function getNomEtude()
    {
        return $this->nomEtude;
    }

    /**
     * Set prixJournee
     *
     * @param float $prixJournee
     * @return Etude
     */
    public function setPrixJournee($prixJournee)
    {
        $this->prixJournee = $prixJournee;
    
        return $this;
    }

    /**
     * Get prixJournee
     *
     * @return float 
     */
    public function getPrixJournee()
    {
        return $this->prixJournee;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->indemnites = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add indemnites
     *
     * @param \Junior\EtudiantBundle\Entity\Indemnites $indemnites
     * @return Etude
     */
    public function addIndemnite(\Junior\EtudiantBundle\Entity\Indemnites $indemnites)
    {
        $this->indemnites[] = $indemnites;
    
        return $this;
    }

    /**
     * Remove indemnites
     *
     * @param \Junior\EtudiantBundle\Entity\Indemnites $indemnites
     */
    public function removeIndemnite(\Junior\EtudiantBundle\Entity\Indemnites $indemnites)
    {
        $this->indemnites->removeElement($indemnites);
    }

    /**
     * Get indemnites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIndemnites()
    {
        return $this->indemnites;
    }
}