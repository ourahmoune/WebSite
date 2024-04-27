<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Sandbox\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/panier', name: 'app_panier')]
class PanierController extends AbstractController
{

    #[Route(
        '/affiche/{id}',
        name: '_affiche',
        requirements: ['id' => '[1-9]\d*'],
    )]
    public function pvAction(EntityManagerInterface $em,Request $request): Response
    {
        $cid = $request->get('id');
        $puser = $em->getRepository(Panier::class)->findByUser( $cid);
        $args = array(
            'p' => $puser,
        );
        return $this->render('panier/affiche.html.twig', $args);
    }
    #[Route(
        '/delete/{id}',
        name: '_delete',
        requirements: ['id' => '[1-9]\d*'],
    )]
    public function dpanAction(int $id, EntityManagerInterface $em,Request $request): Response
    {
        $cid = $request->get('id');
        $puser = $em->getRepository(Panier::class)->find($cid);
        $produit =$puser->getproduit();
        $quantite = $puser->getquantite();

        $produitRepository = $em->getRepository(Produit::class);
        $pproduit = $produitRepository->findOneBy(['id' => $produit]);
        $enstock = $pproduit->getstock();
        $pproduit->setstock($enstock + $quantite);


        if (is_null($puser))
        {
            $this->addFlash('info', 'suppression panier ' .  ' : échec');
            throw new NotFoundHttpException('panier ' .  ' inconnu');
        }

        $em->persist($pproduit);
        $em->remove($puser);
        $em->flush();
        $this->addFlash('info', 'suppression de votre panier réussie');
        return $this->redirectToRoute('app_produit_list');
    }

    #[Route(
        '/vider/{id}',
        name: '_vider',
        requirements: ['id' => '[1-9]\d*'],
    )]
    public function dpantoutAction(EntityManagerInterface $em,Request $request): Response
    {
        $cid = $request->get('id');
        $puser = $em->getRepository(Panier::class)->findOneByUser($cid);
        if (!$puser)
        {
            return $this->redirectToRoute('app_produit_list');
        }
        else{
            while($puser )
            {
                $puser = $em->getRepository(Panier::class)->findOneByUser($cid);
                if(!$puser)
                {
                    $this->addFlash('info', "votre panier vient d'etre vider");
                    return $this->redirectToRoute('app_produit_list');
                }
                else{
                    $produit =$puser->getproduit();
                    $quantite = $puser->getquantite();

                    $produitRepository = $em->getRepository(Produit::class);
                    $pproduit = $produitRepository->findOneBy(['id' => $produit]);
                    $enstock = $pproduit->getstock();
                    $pproduit->setstock($enstock + $quantite);
                    $em->persist($pproduit);
                    $em->remove($puser);

                    $em->flush();
                }
            }

        }

    }




}
