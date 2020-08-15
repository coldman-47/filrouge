<?php

namespace App\Controller;
use App\Entity\Promo;
use App\Entity\Groupe;
use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
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
    public function addpromo(Request $request,ApprenantRepository $repo, SerializerInterface $serializer, EntityManagerInterface $manager)
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
            $apprenants=$groupe->getApprenants();
            // dd($apprenants[0]->getGroupes());
            foreach($apprenants as $apprenant){
                $grp=count($apprenant->getGroupes());
                dd($grp);
            }
            $manager->persist($groupe);
        }
        dd($groupeTab);
        $manager->persist($promos);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    
}
