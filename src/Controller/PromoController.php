<?php

namespace App\Controller;
use App\Entity\Promo;
use App\Entity\Groupe;
use App\Repository\PromoRepository;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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
    public function addpromo(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {

        $promoTab = json_decode($request->getContent(), true);
        $groupeTab = $promoTab['groupes'];
        foreach ($groupeTab as $groupe) {
            $groupes[] = $serializer->denormalize($groupe, Groupe::class);
        }
        unset($promoTab['groupes']);
        $promos = $serializer->denormalize($promoTab, Promo::class);
        foreach ($groupes as $groupe) {
            $promos->addGroupe($groupe);
            $manager->persist($groupe);
        }

        $manager->persist($promos);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
