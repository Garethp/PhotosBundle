<?php

namespace Garethp\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Garethp\PhotosBundle\Entity\Photo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
	    $repo = $this->getDoctrine()->getRepository('GarethpPhotosBundle:Photo');
	    $photos = $repo->createQueryBuilder('p')
	        ->addOrderBy('p.timestamp', 'DESC')
	        ->addOrderBy('p.id', 'DESC')
		    ->getQuery()
	        ;

	    $photos = $photos->getResult();

        return $this->render('GarethpPhotosBundle:Default:index.html.twig', array('photos'=>$photos));
    }

	public function uploadAction(Request $request)
	{
		$photo = new Photo();
		$form = $this->createFormBuilder($photo, array('csrf_protection'=>false))
			->add('file', 'file', array('attr'=>array('accept'=>'image/*', 'capture'=>'camera')))
			->add('upload', 'submit')
			->getForm();

		$form->handleRequest($request);



		if($form->isValid())
		{
			$em = $this->getDoctrine()->getManager();

			$em->persist($photo);
			$em->flush();

			return $this->redirect($this->generateUrl('garethp_photos_homepage'));
		}

		return $this->render('GarethpPhotosBundle:Default:upload.html.twig', array('form'=>$form->createView()));
	}
}
