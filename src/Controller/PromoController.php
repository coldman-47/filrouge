<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\Groupe;
use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/promos/",
     *  name="add_promo",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "add_promo"
     *  }
     * )
     */
    public function addpromo(Request $request, GroupeRepository $repo, SerializerInterface $serializer, EntityManagerInterface $manager)
    {

        $promoTab = json_decode($request->getContent(), true);
        $groupeTab = $promoTab['groupes'];
        foreach ($groupeTab as $groupe) {
            $groupes[] = $serializer->denormalize($groupe, Groupe::class);
        }

        unset($promoTab['groupes']);

        $promos = $serializer->denormalize($promoTab, Promo::class);
        $promos->SetFabrique('SONATEL ACADEMY');
        foreach ($groupes as $groupe) {
            $groupe->setLibelle('GP');
            $promos->addGroupe($groupe);
            $apprenants = $groupe->getApprenants();
            foreach ($apprenants as $apprenant) {
                $grps = $apprenant->getGroupes();
                $gp = 0;
                foreach ($grps as $grp) {
                    if ($grp->getLibelle() === 'GP') {
                        $gp++;
                        if ($gp > 1) {
                            return new Response("Un apprenant ne peut appartenir qu'à une seule promo.");
                        }
                    }
                }
            }
            $manager->persist($groupe);
        }
        $manager->persist($promos);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    /**
     * @Route(
     * name="promo_list",
     * path="api/admin/promo/principal",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=Promo::class,
     * "_api_collection_operation_name"="promo_list"
     * }
     * )
     */
    
    Public function getpromotion(SerializerInterface $serializer,PromoRepository $repo)
    {
        $promo= $repo->findAll();
        $Promot =$serializer->serialize($promo,"json",[
            "groups"=>["promo:read_All"]
        ]);
        return new JsonResponse($Promot,Response::HTTP_OK,[],true);
    }
    
}
