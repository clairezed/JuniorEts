<?php

namespace Junior\EtudiantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Junior\EtudiantBundle\Entity\Etudiant;
use Junior\EtudiantBundle\Entity\Etude;
use Junior\EtudiantBundle\Entity\Acompte;
use Junior\EtudiantBundle\Entity\Participant;
use Junior\EtudiantBundle\Entity\Frais;
use Junior\EtudiantBundle\Form\EtudiantType;
use Junior\EtudiantBundle\Form\Etudiant2Type; // type specifique pour selectEtude
use Junior\EtudiantBundle\Form\Etudiant3Type;
use Junior\EtudiantBundle\Form\EtudeType;
use Junior\EtudiantBundle\Form\AcompteType;
use Junior\EtudiantBundle\Form\FraisType;
use Junior\EtudiantBundle\Form\NewFraisType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;

class EtudiantController extends Controller {

//    public function indexAction($name)
//    {
//        return $this->render('JuniorEtudiantBundle:Default:index.html.twig', array('name' => $name));
//    }

    public function indexAction() {
        return $this->render('JuniorEtudiantBundle:Default:index.html.twig');
    }

    /**
     * Page d'accueil etudiant
     * -> Tableau de bord
     * penser a integrer l'id a partir d'ici
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     * 
     */
    public function dashboardAction() {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getEntityManager();

            $list_frais = $em->getRepository('JuniorEtudiantBundle:Frais')->findFraisbyIdEtudiant($id);
            $list_acompte = $em->getRepository('JuniorEtudiantBundle:Acompte')->findAcomptebyIdEtudiant($id);


            $etudiant = $em->getRepository('JuniorEtudiantBundle:Etudiant')->findOneById($id);
            $listParticipations = $etudiant->getParticipants();
//            $etudes = $etudiant->getEtudes();
//            $statuts = $etudiant->getStatuts();

            return $this->render('JuniorEtudiantBundle:Etudiant:dashboardEtudiant.html.twig', array(
                        'list_frais' => $list_frais,
                        'participations' => $listParticipations,
                        'list_acompte' => $list_acompte,
            ));
        }
    }

    /*     * ************************************************
     * Actions de manipulation des infos etudiant
     * ************************************************* */

    /**
     * Voir les infos personnelles d'un etudiant
     *
     */
    public function showEtudiantAction() {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JuniorEtudiantBundle:Etudiant')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Etudiant entity.');
            }

            return $this->render('JuniorEtudiantBundle:Etudiant:showEtudiant.html.twig', array(
                        'entity' => $entity,
            ));
        }
    }

    /**
     * Displays a form to edit an existing Etudiant entity.
     *
     */
    public function editEtudiantAction() {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('JuniorEtudiantBundle:Etudiant')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Etudiant entity.');
            }

            $editForm = $this->createForm(new Etudiant3Type($id), $entity);
            $request = $this->getRequest();

            if (($request->getMethod() == 'POST')) {
                $editForm->bind($request);
                if ($editForm->isValid()) {
                    $em->persist($entity);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info', 'Vos informations ont bien été modifiées');

                    return $this->redirect($this->generateUrl('junior_etudiant_showEtudiant'));
                }
            }

            return $this->render('JuniorEtudiantBundle:Etudiant:editEtudiant.html.twig', array(
                        'entity' => $entity,
                        'edit_form' => $editForm->createView(),
            ));
        }
    }

    /**
     * Edits an existing Etudiant entity.
     *
     */
    public function updateEtudiantAction(Request $request) {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('JuniorEtudiantBundle:Etudiant')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Etudiant entity.');
            }

            $editForm = $this->createForm(new EtudiantType($id), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('junior_etudiant_showEtudiant', array('id' => $id)));
            }

            return $this->render('JuniorEtudiantBundle:Etudiant:editEtudiant.html.twig', array(
                        'entity' => $entity,
                        'edit_form' => $editForm->createView(),
            ));
        }
    }

    /*     * ************************************************
     * Actions de manipulation des FRAIS
     * ************************************************* */

    public function listFraisAction() {
        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getEntityManager();

            $list_frais = $em->getRepository('JuniorEtudiantBundle:Frais')->findFraisbyIdEtudiant($id);
            $list_rf = $em->getRepository('JuniorEtudiantBundle:RemboursementFrais')->findRFbyIdEtudiant($id);
//            $nbfrais = $list_rf->getFrais()->count();

            return $this->render('JuniorEtudiantBundle:Etudiant:listFrais.html.twig', array(
                        'list_frais' => $list_frais,
                        'list_rf' => $list_rf
//                        'nbfrais' => $nbfrais
            ));
        }
    }

    public function listRFAction() {
        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
            $em = $this->getDoctrine()->getEntityManager();

            $list_rf = $em->getRepository('JuniorEtudiantBundle:RemboursementFrais')->findRFbyIdEtudiant($id);
//            $nbfrais = $list_rf->getFrais()->count();

            return $this->render('JuniorEtudiantBundle:Etudiant:listRF.html.twig', array(
                        'list_rf' => $list_rf
//                        'nbfrais' => $nbfrais
            ));
        }
    }

    public function newFraisAction() {
        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $frais = new Frais;
            $idEtudiant = $user->getId();
            $form = $this->createForm(new NewFraisType($user), $frais);
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
//                $postData = $request->request->get('junior_etudiantbundle_fraistype');
                $form->bind($request);
                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $entityEtud = $em->getRepository('JuniorEtudiantBundle:Etudiant')->find($idEtudiant);
                    $frais->setEtudiant($entityEtud);
                    $frais->setStatutFrais('Enregistré');
                    $em->persist($frais);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info', 'Votre frais a bien été enregistré');
                    return $this->redirect($this->generateUrl('junior_etudiant_listFrais'));
                }
            }
            return $this->render('JuniorEtudiantBundle:Etudiant:newFrais.html.twig', array(
                        'form' => $form->createView(),
                        'user' => $user,
            ));
        }
    }

    public function showRembFraisAction($idRF) {
        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $em = $this->getDoctrine()->getEntityManager();

            $rf = $em->getRepository('JuniorEtudiantBundle:RemboursementFrais')->find($idRF);

            return $this->render('JuniorEtudiantBundle:Etudiant:showRembFrais.html.twig', array(
                        'rf' => $rf
            ));
        }
    }

    /*     * ************************************************
     * Actions de manipulation des ETUDES
     * ************************************************* */

    public function listEtudesAction() {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $id = $user->getId();
//            $cpt = 0;
//            $listeEtudes = array(NULL); //Initialisation des variables : évite une erreur si l'étudiant ne participe à aucune étude
//            $listeStatuts = array(NULL);
            $em = $this->getDoctrine()->getManager();

            $etudiant = $em->getRepository('JuniorEtudiantBundle:Etudiant')->find($id);
            $listeParticipations = $etudiant->getParticipants(); //On récupère la liste des entrées de la table participation correspondant à cet étudiant
//            foreach ($listeParticipations as $participation) { //Pour chaque entrée dans la liste, on récupère l'étude associée et le statut de l'étudiant pour celle-ci
//                $listeEtudes[$cpt] = $participation->getEtude();
//                $listeStatuts[$cpt] = $participation->getStatutEtudiant();
//                $cpt++;
//            }

            return $this->render('JuniorEtudiantBundle:Etudiant:listEtudes.html.twig', array(
                        'participations' => $listeParticipations
            ));
        }
    }

    public function showEtudeAction($idEtude) {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $idEtudiant = $user->getId();

            $em = $this->getDoctrine()->getManager();
            $etude = $em->getRepository('JuniorEtudiantBundle:Etude')->find($idEtude);
            $participant = $em->getRepository('JuniorEtudiantBundle:Participant')->findOneBy(array('etudiant' => $idEtudiant, 'etude' => $idEtude));
            $indemnites = $em->getRepository('JuniorEtudiantBundle:Indemnites')->findOneBy(array('etudiant' => $idEtudiant, 'etude' => $idEtude));
            $acomptes = $indemnites->getAcomptes();

            return $this->render('JuniorEtudiantBundle:Etudiant:showEtude.html.twig', array(
                        'etude' => $etude, 'participant' => $participant, 'indemnites' => $indemnites, 'acomptes' => $acomptes
            ));
        }
    }

    public function selectEtudeAction() {

        $user = $this->getUser();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {
            $idEtudiant = $user->getId();
            $form = $this->createForm(new Etudiant2Type($idEtudiant), $user);
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
                $postData = $request->request->get('junior_etudiantbundle_etudiant2type');
                $idEtude = $postData['etudes'];
                return $this->redirect($this->generateUrl('junior_etudiant_newAcompte', array('idEtude' => $idEtude)));
            }
        }
        return $this->render('JuniorEtudiantBundle:Etudiant:selectEtude.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user,
        ));
    }

    /*     * ************************************************
     * Actions de manipulation des ACOMPTES
     * ************************************************* */

    public function newAcompteAction($idEtude) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $acompte = new Acompte();

        if (null === $user) {
            return $this->render('JuniorEtudiantBundle::layout.html.twig');
        } else {

            $idEtudiant = $user->getId();
            $etude = $em->getRepository('JuniorEtudiantBundle:Etude')->findOneById($idEtude);
            $indemnite = $em->getRepository('JuniorEtudiantBundle:Indemnites')->findOneBy(array('etudiant' => $idEtudiant, 'etude' => $idEtude));
            $acompte->setIndemnite($indemnite);

            $form = $this->createForm(new AcompteType(), $acompte);



            $request = $this->getRequest();

            if ($request->getMethod() == 'POST') {
                $postData = $request->request->get('junior_etudiantbundle_acomptetype');
                $montant = $postData['montantAcompte'];
                $form->bind($request);
                if ($form->isValid() && $indemnite->getNombreAcomptes() < 3 && (($montant + $indemnite->getTotalAcomptes()) <= ($indemnite->getNbJours() * $indemnite->getIndemniteJournee() * 0.8))) {
                    var_dump($indemnite->getNombreAcomptes());
                    $acompte->setIndemnite($indemnite);
                    $acompte->setDateAcompte(new \Datetime());
                    $acompte->setStatutAcompte('En attente');
                    $em->persist($acompte);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info', 'Votre demande d\'acompte a été transmise');
                } else if ($indemnite->getNombreAcomptes() >= 3) {
                    $this->get('session')->getFlashBag()->add('erreur', 'Erreur : vous avez déja demandé trois acomptes pour cette étude');
                } else if (($montant + $indemnite->getTotalAcomptes()) > ($indemnite->getNbJours() * $indemnite->getIndemniteJournee() * 0.8)) {
                    $this->get('session')->getFlashBag()->add('erreur', 'Erreur : vous avez dépassé le montant autorisé pour cette étude');
                } else {
                    $this->get('session')->getFlashBag()->add('erreur', 'Erreur lors de la transmission de la demande');
                }

                return $this->redirect($this->generateUrl('junior_etudiant_listEtudes'));
            }
            return $this->render('JuniorEtudiantBundle:Etudiant:newAcompte.html.twig', array(
                        'form' => $form->createView(), 'idEtude' => $idEtude, 'etude' => $etude
            ));
        }
    }

}
