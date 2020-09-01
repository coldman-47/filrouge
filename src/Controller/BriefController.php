<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Promo;
use App\Entity\Groupe;
use App\Entity\Livrable;
use App\Entity\EtatBrief;
use App\Entity\BriefMaPromo;
use App\Entity\BriefLivrable;
use App\Entity\BriefApprenant;
use App\Repository\BriefRepository;
use App\Repository\PromoRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $brief = $request->request->All();
        $img = $request->files->get("imagePromo");

        $img = fopen($img->getRealPath(), "rb");
        $tag = explode(",", $brief["Tag"]);
        // $formateur=$brief['formateur'];
        $niveau = explode(",", $brief["Niveaux"]);
        $ressource = explode(",", $brief["Ressource"]);
        $entityManager = $this->getDoctrine()->getManager();
        //transformation en objet
        $Brief = $serializer->denormalize($brief, Brief::class);
        $Brief->setImagePromo($img);

        foreach ($tag as $key) {
            $Brief->addTag($entityManager->getRepository(Tag::class)->find($key));
        }


        foreach ($niveau as $key) {
            $Brief->addNiveau($entityManager->getRepository(Niveau::class)->find($key));
        }


        foreach ($ressource as $key) {
            $Brief->addRessource($entityManager->getRepository(Ressource::class)->find($key));
        }

        $manager->persist($Brief);

        if (isset($brief['groupe']) && !empty($Brief['groupe'])) {
            $groupe = explode(",", $brief["Groupe"]);
            foreach ($groupe as $key) {
                $grp = $entityManager->getRepository(Groupe::class)->find($key);
                $etatBrief = new EtatBrief;
                $etatBrief->setGroupe($grp);
                $etatBrief->setBrief($Brief);
                $manager->persist($etatBrief);

                $briefMaPromo = new BriefMaPromo;
                $briefMaPromo->setBrief($Brief);
                $briefMaPromo->setPromo($grp->getPromos());
                $manager->persist($briefMaPromo);
            }
        }

        $manager->flush();
        fclose($img);

        return $this->json($Brief, 200);
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
    public function getOneBriefByPromo($id, $ID, PromoRepository $repoPromo)
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

    function briefByPromo($id, PromoRepository $repoPromo)
    {
        $promo = $repoPromo->find($id);
        if (!empty($promo)) {
            return $promo;
        }
        return false;
    }


    /**
     * @Route(
     * "/api/formateur/promo/{id}/brief/{id2}",
     *  name="editBriefByPromo",
     *  methods = {"PUT"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_item_operation_name" = "editBriefByPromo"
     *  }
     * )
     */
    public function editBriefByPromo(BriefRepository $repoBrief, $id, $id2, PromoRepository $repoPromo, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $promo = $this->briefByPromo($id, $repoPromo);
        if (!empty($promo)) {
            foreach ($promo->getBriefMaPromos() as $briefMaPromo) {
                if ($briefMaPromo->getBrief()->getId() == $id2) {
                    $brief = $repoBrief->find($id2);

                    $briefTab = json_decode($request->getContent(), true);

                    //archiver brief
                    if (isset($briefTab["dropBrief"])) {
                        $dropBrief = $serializer->denormalize($briefTab["dropBrief"], Brief::class);
                        $dropBrief->setArchive(true);
                        // $idBrief=$dropBrief->getId();
                        // if($brief->getId() == $idBrief){
                        //     $manager->remove($dropBrief);
                        // }

                        $manager->persist($dropBrief);
                    }


                    if (isset($briefTab['livrable'])) {
                        $livrable = $serializer->denormalize($briefTab["livrable"], Livrable::class);
                        $briefLivrable = new BriefLivrable();
                        $briefLivrable->setLivrable($livrable);
                        $briefLivrable->setUrl($briefTab["livrable"]["url"]);
                        $brief->addBriefLivrable($briefLivrable);
                    }

                    if (isset($briefTab["dropLivrable"])) {
                        $dropLivrable = $serializer->denormalize($briefTab["dropLivrable"], Livrable::class);
                        $dropLivrable->setArchive(true);
                        $idLivrable = $dropLivrable->getId();

                        foreach ($brief->getBriefLivrables() as $briefLivrable) {
                            if ($briefLivrable->getLivrable()->getId() == $idLivrable) {
                                $brief->removeBriefLivrable($briefLivrable);
                                $manager->remove($briefLivrable);
                            }
                        }
                    }

                    //ajout de ressource
                    if (isset($briefTab['ressource'])) {
                        $ressource = $serializer->denormalize($briefTab["ressource"], Ressource::class);
                        $brief->addRessource($ressource);
                    }
                    //supression de ressource
                    if (isset($briefTab["dropRessource"])) {
                        $dropRessource = $serializer->denormalize($briefTab["dropRessource"], Ressource::class);
                        $idRessource = $dropRessource->getId();

                        foreach ($brief->getRessources() as $ressource) {
                            if ($ressource->getId() == $idRessource) {
                                $brief->removeRessource($ressource);
                                $manager->remove($dropRessource);
                            }
                        }
                    }
                    //ajout niveau
                    if (isset($briefTab["niveau"])) {
                        $niveau = $serializer->denormalize($briefTab["niveau"], Niveau::class);
                        $brief->addNiveau($niveau);
                    }


                    $manager->persist($brief);
                    $manager->flush();

                    return new Response("success");
                }

                $briefMaPromo = null;
            }
        }
        return new Response("Promo inéxistante!");
    }

    /**
     * @Route(
     * "api/formateurs/briefs/{id}",
     *  name="duplicateBrief",
     *  methods = {"POST"},
     *  
     * )
     */
    public function duplicateBrief($id, BriefRepository $repoBrief, EntityManagerInterface $em)
    {
        $brief = $repoBrief->find($id);

        if ($brief) {

            $Brief = new Brief();

            $Brief->setLangue($brief->getLangue())
                ->setNomBrief($brief->getNomBrief())
                ->setDescription($brief->getDescription())
                ->setContexte($brief->getContexte())
                ->setModalitePedagogique($brief->getModalitePedagogique())
                ->setCritereEvaluation($brief->getCritereEvaluation())
                ->setImagePromo($brief->getImagePromo())
                ->setArchiver($brief->getArchiver())
                ->setCreateAt($brief->getCreateAt())
                ->setEtat($brief->getEtat())
                ->setFormateur($brief->getFormateur());
            $em->persist($Brief);
            $em->flush();
        }

        return new Response('duplication avec succes');
    }

    /**
     * @Route(
     * "api/formateurs/promo/{id}/brief/{id2}",
     *  name="assignationBrief",
     *  methods = {"PUT"},
     *  
     * )
     */
    public function AssignationBrief(BriefRepository $repoBrief, GroupeRepository $repoGroupe, BriefMaPromoRepository $repoBriefMaPromo, ApprenantRepository $repoApprenant, $id2, $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $briefTab = $request->getContent();
        $briefTab = $serializer->decode($briefTab, 'json');
        $briefMaPromo = $repoBriefMaPromo->findBriefMaPromo($id2, $id)[0];
        $Brief = $repoBrief->find($id2);
        // dd($briefMaPromo);
        if (isset($briefTab['Assigner_groupe'])) {
            foreach (explode(',', $briefTab['Assigner_groupe']) as $groupe) {
                $etatBrief = new EtatBrief();
                // $promo = new Promo();
                $groupes = $repoGroupe->find($groupe);
                $etatBrief->setGroupe($groupes);
                $etatBrief->setBrief($Brief);
                $manager->persist($etatBrief);
            }
        }

        if (isset($briefTab['assigner_apprenant'])) {
            $apprenant = $repoApprenant->find($briefTab['assigner_apprenant']);
            $BriefApprenant = new BriefApprenant();
            // $briefMaPromo = new BriefMaPromo();
            $BriefApprenant->setApprenant($apprenant);
            $BriefApprenant->setBriefmapromo($briefMaPromo);
            $manager->persist($BriefApprenant);
        }


        // $manager->persist($brief);
        $manager->flush();
        return new Response("success");
    }

    /**
     * @Route(
     *  "/api/apprenants/{id}/groupe/{id2}/livrables",
     *  name="addlivrablesByGroupeApprenant",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "addlivrablesByGroupeApprenant"
     *  }
     * )
     */
    public function addlivrablesByGroupeApprenant(ApprenantRepository $repoApprenant, $id, $id2, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $apprenantTab = $repoApprenant->find($id);
        $apprenant = $serializer->denormalize($apprenantTab, Brief::class);
        foreach ($apprenant->getLivrableApprenants() as $livrableApprenant) {
            if ($livrableApprenant->getApprenant()->getId() == $id2) {
                $livrable = new Livrable();
                $groupe = new Groupe();
                $livrableApprenant->getLivrable()->getId($livrable);
                $apprenant->getGroupe()->getId($groupe);
                $apprenant->add($livrable);
            }
        }

        $manager->persist($livrable);
        $manager->flush();
        return new Response("success");
    }
}
