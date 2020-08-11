<?php

namespace App\Controller;

use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    
    /**
     * @Route(
     *  "/api/admin/groupes",
     *  name="add_groupes",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Groupe::class,
     *      "_api_collection_operation_name" = "add_groupes"
     *  }
     * )
     */
    public function addGroup(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        //recuperation des données en json
        $groupeTab = json_decode($request->getContent(), true);
        $promosTab = $groupeTab['promos'];

        //creation promo s'il n'y a en pas
        foreach ($promosTab as $promo) {
            $promos[] = $serializer->denormalize($promo, Promo::class);
        }
        $group = $serializer->denormalize($groupeTab, Groupe::class);

        //ajout promo dans groupe, vice versa
        foreach ($promos as $promo) {
            $group->addPromo($promo);
            $promo->addGroupe($group);
            $manager->persist($promo);
        }
        

        $manager->persist($group);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
