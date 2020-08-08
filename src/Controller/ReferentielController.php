<?php

namespace App\Controller;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/referentiels/",
     *  name="addreferentiel",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Referentiel::class,
     *      "_api_collection_operation_name" = "addreferentiel"
     *  }
     * )
     */
    public function index(Request $request, GroupeCompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $referentielTab = json_decode($request->getContent(), true);

        $grpCompetences = $repo->findOneBy(['libelle' => $referentielTab["competences"]]);

        $referentiel = $serializer->denormalize($referentielTab, Referentiel::class);
        $referentiel->addGrpCompetence($grpCompetences);

        $manager->persist($referentiel);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
