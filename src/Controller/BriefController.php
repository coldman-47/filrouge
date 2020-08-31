<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Groupe;
use App\Entity\EtatBrief;
use App\Entity\BriefMaPromo;
use App\Entity\Promo;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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
                // création nouvelle instance d'association entre brief et groupe
                $etatBrief = new EtatBrief();
                // création nouvelle instance d'association entre brief et promo
                $briefMaPromo = new BriefMaPromo();
                $group = $serializer->denormalize(trim($groupe), Groupe::class);
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
        return new JsonResponse("Success", 200);
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
                    foreach ($group->getEtatBriefs() as $etatBrief) {
                        if ($etatBrief->getStatut() === "En cours") {
                            $briefs[] = $repoBrief->findOneBy(['id' => $etatBrief->getBrief()->getId()]);
                        }
                    }
                    return $this->json($briefs, 200, [], ["groups" => ["briefs"]]);
                }
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
            return $this->json($briefs, 200, [], ["groups" => ["briefs"]]);
        }
        return new Response("Promo inéxistante!");
    }

    /**
     * @Route(
     * "/api/formateur/{id}/briefs/{status}",
     *  name="getBriefByPromoStatus",
     *  methods = {"GET"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "getBriefByPromoStatus"
     *  }
     * )
     */
    public function getBriefByPromoStatus(BriefRepository $repoBrief, $id, FormateurRepository $repoFormateur, string $status)
    {
        $briefs = [];
        if (in_array(strtolower($status), ["assigne", "brouillon"])) {
            $formateur = $repoFormateur->find($id);
            if (!empty($formateur)) {
                foreach ($formateur->getGroupe() as $group) {
                    foreach ($group->getEtatBriefs() as $etatBrief) {
                        $brief = $repoBrief->findBy(['id' => $etatBrief->getBrief()->getId(), 'etat' => $status]);
                        if (!empty($brief)) {
                            $briefs[] = $brief;
                        }
                    }
                }
                return $this->json($briefs, 200, [], ["groups" => ["briefs"]]);
            }
            return new Response("Promo inéxistante!");
        }
        return new Response("Accès refusé!");
    }

    /**
     * @Route(
     * "/api/formateur/promo/{id}/briefs/{ID}",
     *  name="getOneBriefByPromo",
     *  methods = {"GET"}
     * )
     */
    public function getOneBriefByPromo(SerializerInterface $normalizer, $id, $ID, PromoRepository $repoPromo)
    {
        $promo = $repoPromo->find($id);
        if (!empty($promo)) {
            foreach ($promo->getBriefMaPromos() as $briefMaPromo) {
                if ($briefMaPromo->getBrief()->getId() == $ID) {
                    $brief = $briefMaPromo->getBrief();
                    return $this->json($brief, 200, [], ["groups" => ["briefs"]]);
                }
            }
            if (!isset($brief)) {
                return new Response("Brief inéxistant!");
            }
        }
        return new Response("Promo inéxistante!");
    }
}
