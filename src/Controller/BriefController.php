<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Groupe;
use App\Entity\EtatBrief;
use App\Entity\BriefMaPromo;
use App\Entity\Promo;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Json;

class BriefController extends AbstractController
{
    /**
     * @Route(
     *  "/api/formateur/brief/",
     *  name="add_brief",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "add_brief"
     *  }
     * )
     */
    public function addBrief(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $briefTab = $request->request->All();
        $brief = $serializer->denormalize($briefTab, Brief::class);
        foreach (explode(',', $briefTab['Groupe']) as $groupe) {
            $etatBrief = new EtatBrief();
            $briefMaPromo = new BriefMaPromo();
            $group = $serializer->denormalize(trim($groupe), Groupe::class);
            $groupes[] = $group;
            $etatBrief->setGroupe($group);
            $etatBrief->setStatut('En cours');
            $brief->addEtatBrief($etatBrief);
            $briefMaPromo->setPromo($group->getPromo());
            $brief->addBriefMaPromo($briefMaPromo);
        }
        $manager->persist($brief);
        // dd($brief->getBriefMaPromos()[0]);
        $manager->flush();
        return new Response("success");
    }

    /**
     * @Route(
     * "/api/formateur/promo/{id}/groupe/{ID}/briefs",
     *  name="getBriefByGroupPromo",
     *  methods = {"GET"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "getBriefByGroupPromo"
     *  }
     * )
     */
    public function getBriefByGroupPromo(BriefRepository $repo, $id, $ID, SerializerInterface $serializer)
    {
        $promo = $serializer->denormalize("api/admin/promos/$id/", Promo::class);
        foreach ($promo->getGroupes() as $group) {
            if ($group->getId() == $ID) {
                break;
            }
            $group = null;
        }
        foreach ($group->getEtatBriefs() as $etatBrief) {
            $briefs[] = $repo->findOneBy(['id' => $etatBrief->getBrief()->getId()]);
        }
        return $this->json($briefs, 200, [], ["grous" => ["briefs"]]);
        dd($briefs);
    }
}
