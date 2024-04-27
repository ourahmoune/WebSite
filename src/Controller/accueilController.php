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


#[Route('/', name: 'accueil')]
class accueilController extends AbstractController
{
    #[Route('/nb_article/' ,name:'_nb_article')]
    public function menuAction(EntityManagerInterface $em){
        $client = $this->getUser();
        $nb_article = 0;
        if(!is_null($client)){
            $roles = $client->getRoles();
            $trouve = false ;
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN" || $role == "ROLE_USER")
                    $trouve = true ;
            }
            $paniers = $em->getRepository(Panier::class)->findBy(['user'=>$client]);
            if($trouve){
                foreach ($paniers as $panier){
                    $nb_article =  $nb_article + $panier->getQuantite();
                }
            }

        }
        return $this->render('panier/nb_article.html.twig',['nb_article'=>$nb_article]);
    }
    #[Route('/', name: '')]
    public function AccueilAction(): Response
    {
        return $this->render('Layouts/medieum.html.twig');
    }


    //supprimmer un article de mon panier
    #[Route(
        '/dpan/{id}',
        name: '_dpan',
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
    //vidr mon panier

    //commander des articles depuis du panier

    #[Route(
        '/cmd/{id}',
        name: '_cmd',
        requirements: ['id' => '[1-9]\d*'],
       )]
    public function cmdAction(EntityManagerInterface $em,Request $request): Response
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
            $this->addFlash('info', 'vos commandes sont en route!!!!!');
            return $this->redirectToRoute('app_produit_list');
        }
        else{
        $em->remove($puser); 
          
        $em->flush();
        }
       
        }
        
       }
    }


}  







