<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Groupe;
use App\Entity\EtatBrief;
use App\Entity\BriefMaPromo;
use App\Entity\Promo;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
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
        if (isset($briefTab['Groupe'])) {
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
            $brief->setEtat('Assigné');
        } else {
            $brief->setEtat('Brouillon');
        }
        $manager->persist($brief);
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
    public function getBriefByGroupPromo(BriefRepository $repoBrief, $id, $ID, PromoRepository $repoPromo)
    {
        $briefs = [];
        $promo = $repoPromo->find($id);
        if (!empty($promo)) {
            foreach ($promo->getGroupes() as $group) {
                if ($group->getId() == $ID) {
                    break;
                }
                $group = null;
            }
            if (!empty($group)) {
                foreach ($group->getEtatBriefs() as $etatBrief) {
                    $briefs[] = $repoBrief->findOneBy(['id' => $etatBrief->getBrief()->getId()]);
                }
                return $this->json($briefs, 200, [], ["grous" => ["briefs"]]);
            }
            return new Response("Ce groupe n'éxiste pas!");
        }
        return new Response("Promo inéxistante!");
    }

    /**
     * @Route(
     * "/api/formateur/promo/{id}/briefs",
     *  name="getBriefByPromo",
     *  methods = {"GET"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "getBriefByPromo"
     *  }
     * )
     */
    public function getBriefByPromo(BriefRepository $repoBrief, $id, PromoRepository $repoPromo)
    {
        $briefs = [];
        $promo = $repoPromo->find($id);
        if (!empty($promo)) {
            foreach ($promo->getBriefMaPromos() as $briefMaPromo) {
                $briefs[] = $repoBrief->find($briefMaPromo->getBrief()->getId());
            }
            return $this->json($briefs, 200, [], ["grous" => ["briefs"]]);
        }
        return new Response("Promo inéxistante!");
    }

    /**
     * @Route(
     * "/api/formateur/promo/{id}/briefs/{status}",
     *  name="getBriefByPromoStatus",
     *  methods = {"GET"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "getBriefByPromoStatus"
     *  }
     * )
     */
    public function getBriefByPromoStatus(BriefRepository $repoBrief, $id, PromoRepository $repoPromo, $status)
    {
        $briefs = [];
        if (in_array(strtolower($status), ["assigne", "brouillon", "valide"])) {
            $promo = $repoPromo->find($id);
            if (!empty($promo)) {
                foreach ($promo->getBriefMaPromos() as $briefMaPromo) {
                    $brief = $repoBrief->findOneBy(["id" => $briefMaPromo->getBrief()->getId(), "etat" => $status]);
                    if (!empty($brief)) {
                        $briefs[] = $brief;
                    }
                }
                return $this->json($briefs, 200, [], ["groups" => ["briefs"]]);
            }
            return new Response("Promo inéxistante!");
        }
        return new Response("Accès refusé!");
    }
}
