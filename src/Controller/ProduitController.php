<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Sandbox\Produit;
use App\Entity\User;
use App\Form\PanierType;
use App\Form\Sandbox\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/produit', name: 'app_produit')]
class ProduitController extends AbstractController
{


    #[Route('/add', name: '_add')]
    public function prodAddAction(EntityManagerInterface $em, Request $request): Response
    {
        $p = new Produit();

        $form = $this->createForm(ProduitType::class, $p);
        $form->add('send', SubmitType::class, ['label' => 'ajouter des produits']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($p);
            $em->flush();
            $this->addFlash('info', 'ajouter un nouveau produit réussi');
            return $this->redirectToRoute('app_produit_add');
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'formulaire ajouter un  produit incorrect');

        $args = array(
            'myform' => $form->createView(),
        );
        return $this->render('produit/add.html.twig', $args);
    }


    #[Route('/list/' , name:'_list')]
    public function ListProduitAction (EntityManagerInterface $em ):Response {
        $client= $this->getUser();
        $quantitePaniers=array();
        $produits =  $em->getRepository(Produit::class)->findAll() ;
        $forms = array() ;
        foreach ($produits as $produit){
            $quantiteMax = $produit->getStock();
            $panier= $em->getRepository(Panier::class)->findOneBy([
                'user'=>$client,
                'produit'=>$produit,
            ]);
            $quantitePanier =0;
            if(!is_null($panier)){
                $quantitePanier = $panier->getQuantite();
            }
            $quantitePaniers[$produit->getId()]=$quantitePanier;
            $form= $this->createForm(PanierType::class,null,
                [
                    'quantiteMax' => $quantiteMax,
                    'quantitePanier' => $quantitePanier,
                    'action'=>$this->generateUrl('app_produit_modifier',['idp'=>$produit->getId()]),
                ]
            );
            $form->add('send',SubmitType::class , ['label'=>'modifier']);
            $forms[$produit->getId()] = $form->createView();
        }
        return $this->render('Produit/list.html.twig',['quantitepaniers'=>$quantitePaniers,'produits'=>$produits , 'forms'=>$forms]);
    }


    #[Route('/modifier/{idp}' , name:'_modifier')]
    public function modfierAction (EntityManagerInterface $em , Request  $request , int $idp) : Response{
        $produit = $em->getRepository(Produit::class)->find($idp);
        $client = $this->getUser();
        $quantiteMax = $produit->getStock();
        $panier = $em->getRepository(Panier::class)->findOneBy([
            'user'=>$client,
            'produit'=>$produit,
        ]);
        $quantitePanier =0;
        if(!is_null($panier)){
            $quantitePanier = $panier->getQuantite();
        }
        $form= $this->createForm(PanierType::class,null,
            [
                'quantiteMax' => $quantiteMax,
                'quantitePanier' => $quantitePanier,
                'action'=>$this->generateUrl('app_produit_modifier',['idp'=>$produit->getId()]),
            ]
        );

        $form->add('send',SubmitType::class , ['label'=>'modifier']);
        $form->handleRequest($request) ;
        $q_avant =0 ;
        $q = $form->getData()->getQuantite();
        $panier= $em->getRepository(Panier::class)->findOneBy(
            [
                'user'=>$client,
                'produit'=>$produit,
            ]
        );
        if(!is_null($panier)){
            $q_avant = $panier->getQuantite();
        }
        else{
            $panier = new Panier();
        }
        if ($form->isSubmitted()){
            $panier
                ->setProduit($produit)
                ->setUser($client)
                ->setQuantite($q + $q_avant) ;
            $em->persist($panier);

            $em->persist($produit);
            $produit->setStock($produit->getStock()-$q);
            if($q + $q_avant == 0)
                $em->remove($panier);
            $em->flush();
            $this->addFlash('info','la quantite de produit  '.$produit->getLibelle().'  vient d\'étre changer dans votre panier');
            return $this->redirectToRoute('app_produit_list');
        }
        return $this->redirectToRoute('app_produit_list');


    }








/*
    #[Route('/delete/{id}' , name :'_delete')]
    public function DeleteProuitAction(EntityManagerInterface $em , int $id) : Response
    {
        $produitsRep = $em->getRepository(Produit::class);
        $produit=$produitsRep->find($id);
        if(is_null($produit)){
            throw new NotFoundHttpException('produit'.$id.'inexistant');
        }
        $em->persist($produit);
        $em->remove($produit);
        $em->flush();
        $this->addFlash('info','le produit'.$id.'a ete bien supprimé');
        return $this->redirectToRoute('app_produit_list');
    }

*/

}
