<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/grpcompetences/",
     *  name="add_grp_competences",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = GroupeCompetence::class,
     *      "_api_collection_operation_name" = "add_grp_competences"
     *  }
     * )
     */
    public function addGrpCompetence(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $grpCompeTab = json_decode($request->getContent(), true);
        $competencesTab = $grpCompeTab['competences'];
        foreach ($competencesTab as $competence) {
            $competences[] = $serializer->denormalize($competence, Competence::class);
        }
        $grpCompetences = $serializer->denormalize($grpCompeTab, GroupeCompetence::class);

        foreach ($competences as $competence) {
            $grpCompetences->addCompetence($competence);
            $competence->addGroupeCompetence($grpCompetences);
            $manager->persist($competence);
        }

        $manager->persist($grpCompetences);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/competences/",
     *  name="addcompetences",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Competence::class,
     *      "_api_collection_operation_name" = "addcompetences"
     *  }
     * )
     */
    public function addCompetence(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, GroupeCompetenceRepository $repo)
    {
        $competenceTab = json_decode($request->getContent(), true);
       
        $grpCompetences = $repo->findOneBy(['libelle' => $competenceTab["grpCompetences"]]);

        $competence = $serializer->denormalize($competenceTab, Competence::class);
        $competence->addGroupeCompetence($grpCompetences);

        $manager->persist($competence);
        $manager->persist($grpCompetences);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
