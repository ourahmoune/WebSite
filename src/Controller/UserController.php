<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Sandbox\Produit;
use App\Entity\User;
use App\Form\Sandbox\newType;
use App\Service\PasswordChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    private $passwordHasher;
    #[Route('/add/{role}/{id}',
        name: '_add' ,
    )]
    public function userAddAction(  int $id ,string $role , EntityManagerInterface $em, Request $request,PasswordChecker $passwordChecker): Response
    {

        if($id != 0)
            $p = $em->getRepository(User::class)->find($id);
        else
            $p = new User();

        $form = $this->createForm(newType::class, $p);
        $form->add('send', SubmitType::class, ['label' => 'valider']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($passwordChecker->isStrong($p->getPassword())) {
                $p->setPassword($this->passwordHasher->hashPassword($p, $p->getPassword()));
                $p->setRoles([$role]);
                $em->persist($p);
                $em->flush();
                $this->addFlash('info', 'le compte '.$role.' est bien creé');

                if($role = "ROLE_ADMIN"){
                    return $this->redirectToRoute('accueil');
                }
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('warning', 'Le mot de passe n\'est pas suffisamment robuste');
            }}

        if ($form->isSubmitted())
            $this->addFlash('info', 'formulaire creer un  compte incorrect ou Le mot de passe n\'est pas suffisamment robuste');
        $user = $form->getData();
        if(!is_null($user)){
            $user->SetPassword("") ;
            $form = $this->createForm(newType::class, $user);
            $form->add('send', SubmitType::class, ['label' => 'valider']);

        }

        $args = array(
            'myform' => $form->createView(),
        );
        return $this->render('user/add.html.twig', $args);
    }

    #[Route(
        '/delete/{id}',
        name: '_delete',
        requirements: ['id' => '[1-9]\d*'],
    )]
    public function deleteAction(int $id, EntityManagerInterface $em,Request $request): Response
    {
        $userRepository = $em->getRepository(User::class);
        $iduser = $userRepository->find($id);

        if (is_null($iduser))
        {
            $this->addFlash('info', 'suppression client ' .  ' : échec');
            throw new NotFoundHttpException('client '  . ' inconnu');
        }
        else
        {
            $cid = $request->get('id');
            $puser = $em->getRepository(Panier::class)->findOneByUser($cid);
            if (!$puser)
            {
                $this->addFlash('info', "le panier d'utilisateur vient d'etre vider ");

            }
            else{
                while($puser )
                {
                    $puser = $em->getRepository(Panier::class)->findOneByUser($cid);

                    if(!$puser)
                    {
                        $this->addFlash('info', '');

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
                    }}

            }
            $em->remove($iduser);
            $em->flush();
            $this->addFlash('info', 'suppression client ' . $id . ' réussie');

            return $this->redirectToRoute('app_user_modif');
        }}




    #[Route('/modif', name: '_modif')]
    public function showTable(EntityManagerInterface $em): Response
    {
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'tableData' => $user,
        ]);
    }

}
