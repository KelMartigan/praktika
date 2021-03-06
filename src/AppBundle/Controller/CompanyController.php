<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Firma;
use AppBundle\Form\FirmaType;
use AppBundle\Entity\Praktikum;

class CompanyController extends Controller
{														/* Firma Funktionen */
	
	/** Neue Firma in die Datenbank aufnehmen
    * @Route("/create/firma", name="formfirma")
    * @Security("has_role('ROLE_USER')")
    */
    public function firmaAction(Request $request)
    {
        $firma = new Firma();
        $form = $this->createForm('AppBundle\Form\FirmaType', $firma);
		
		$form->handleRequest($request);
		
        if($form->isSubmitted() && $form->isValid())
		{
            $firma = $form->getData();
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($firma);
            $em->flush();
			
            return $this->redirectToRoute('formcontact', array(
					'id' => $firma->getId()
			));
        }
		
		return $this->render('default/form/firma.html.twig', array(
				'form' => $form->createView()
		));
    }
	
	/** Liste aller eingetragenen Firmen anzeigen
    * @Route("/show/firma", name="showfirma")
    * @Security("has_role('ROLE_USER')")
    */
    public function showFirmaAction(Request $request)
    {
        $firmen = $this->getDoctrine()
                        ->getRepository('AppBundle:Firma')
                        ->findAll();
		
        return $this->render('default/firmen.html.twig', array(
                'firmen' => $firmen
        ));
    }
	
	/** Eine Firma aus der Datenbank mit Editierfunktion anzeigen
     * @Route("/edit/firma/{id}", name="editfirma")
     * @Security("has_role('ROLE_USER')")
     */
    public function editFirmaAction(Request $request, $id)
    {
        $firma = $this->getDoctrine()
						->getRepository('AppBundle:Firma')
                        ->find($id);
        $form = $this->createForm("AppBundle\Form\FirmaType", $firma);
		$form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
            $em->persist($firma);
            $em->flush();
			
            if ($request->request->has('delete'))
            {
				return $this->redirectToRoute('deletefirma', array(
						'id' => $id
                ));
            }
            
            return $this->redirectToRoute('editfirma', array(
					'id' => $id,
					'message' => "Daten wurden erfolgreich gespeichert!",
            ));
        }
        
        return $this->render('edit/firma.html.twig', array(
				'firma' => $firma,
				'form' => $form->createView(),
				'message' => $request->query->get('message')
        ));
    }
	
	/** Firmeneintrag aus der Datenbank löschen, Ansprechpartner zu dieser Firma werden ebenfalls gelöscht (cascade)
     * @Route("/delete/firma/{id}", name="deletefirma")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteFirmaAction(Request $request, $id)
    {
        $firma = $this->getDoctrine()
                        ->getRepository('AppBundle:Firma')
						->find($id);
        $name = $firma->getName();
		$ansprechpartner = $this->getDoctrine()
                        ->getRepository('AppBundle:Ansprechpartner')
						->findByFirma($firma);
		
		$praktika = $this->getDoctrine()
                        ->getRepository('AppBundle:Praktikum')
						->findByFirma($firma);
		$kontakte = $this->getDoctrine()
                        ->getRepository('AppBundle:Kontakt')
						->findByAnsprechpartner($ansprechpartner);
		
        $em = $this->getDoctrine()->getManager();
		foreach($praktika AS $praktikum)
		{
			$em->remove($praktikum);
		}
		foreach($kontakte AS $kontakt)
		{
			$em->remove($kontakt);
		}
        $em->remove($firma);
        $em->flush();
		
        $msg = "Die Firma: " . $name . " wurde erfolgreich gelöscht!";
        return $this->render('default/confirm.html.twig', array(
				'message' => $msg
        ));
    }
}