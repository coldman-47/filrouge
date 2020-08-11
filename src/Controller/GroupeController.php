<?php

namespace App\Controller;

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
        $apprenantsTab = $groupeTab['apprenants'];

        //creation apprenant s'il n'y a en pas
        foreach ($apprenantsTab as $apprenant) {
            $apprenants[] = $serializer->denormalize($apprenant, Competence::class);
        }
        $group = $serializer->denormalize($groupeTab, GroupeCompetence::class);

        //ajout apprenant dans groupe, vice versa
        foreach ($apprenants as $apprenant) {
            $group->addApprenant($apprenant);
            $apprenant->addGroupe($group);
            $manager->persist($apprenant);
        }
        

        $manager->persist($group);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    
}
