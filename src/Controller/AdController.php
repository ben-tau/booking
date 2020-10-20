<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Permet d'afficher une liste d'annonces
     * @Route("/ads", name="ads_list")
     */
    public function index(AdRepository $repo)
    {
        
        // via $repo, on va aller chercher ttes les annonces via la méthode findAll
        
        $ads = $repo->findAll();
        
        return $this->render('ad/index.html.twig', [
            'controller_name' => 'Nos annonces',
            'ads' => $ads
            ]);
    }
        
    /**
    * Permet de créer une annonce
    * @Route("ads/new",name="ads_create")
    * @return Response
    */
    public function create(Request $request,EntityManagerInterface $entityManager)
    {
        //fabricant de formulaire : FORMBUILDER

        $ad = new Ad();

        // on lance la fabrication et la config de notre form
        $form = $this->createForm(AnnonceType::class,$ad);

        // récup des données du form
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid())
        {
            // si le form est soumis ET valide, on demande à doctrine de save les données ds un objet $entityManager

            // pr chq img supplémentaire ajoutée

            foreach($ad->getImages() as $image)
            {
                // on relie l'img à l'annonce et on modifie l'annonce
                $image->setAd($ad);

                //on save les imgs
                $entityManager->persist($image);
            }

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash('success',"Annonce <strong>{$ad->getTitle()}</strong> créée avec succès");

            return $this->redirectToRoute('ads_single',['slug' => $ad->getSlug()]);
        }

        return $this->render('ad/new.html.twig',['form'=>$form->createView()]);
    }

    /**
     * Permet d'afficher une seule annonce
     * @Route("/ads/{slug}",name="ads_single")
     * @return Response
     */
    public function show($slug,Ad $ad)
    {
        // je récup l'annonce qui correspond au slug
        // X = 1 chp de la table, à préciser à la place de X
        // $ad = $repo->findOneByX
        // findByX -> ca renvoie un tableau // findOneByX -> renvoie un élement
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',['ad'=>$ad]);
    }

    /**
     * Permet d'éditer et de modif un article
     * @Route("/ads/{slug}/edit", name="ads_edit")
     *
     * @return Response
     */
    public function edit(Ad $ad,Request $request,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AnnonceType::class,$ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $entityManager->persist($image);
            }

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash("success","Les modifications ont été effectuées.");

            return $this->redirectToRoute('ads_single',['slug'=>$ad->getSlug()]);
        }

        return $this->render('ad/edit.html.twig',['form'=>$form->createView(),'ad'=>$ad]);
    }

}
