<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\Competence;
use App\Entity\Commentaire;
use App\Entity\FilDiscution;
use App\Entity\LivrablePartiel;
use App\Repository\BriefRepository;
use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
use App\Entity\ApprenantLivrablePartiel;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use App\Repository\ReferentielRepository;
use App\Repository\BriefMaPromoRepository;
use App\Repository\FilDiscutionRepository;
use App\Controller\LivrablePartielController;
use App\Repository\LivrablePartielRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompetenceValideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ApprenantLivrablePartielRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route(
     * name="form_promo_ref_comp",
     * path="api/formateur/promo/{id}/referentiels/{ID}/competence",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=Promo::class,
     * "_api_collection_operation_name"="form_promo_ref_comp"
     * }
     * )
     */

    public function form_promo_ref_comp(SerializerInterface $serializer,$id,$ID, PromoRepository $repo,CompetenceRepository $repoComp)
    {
        $promos = $repo->find($id);
        if (!empty($promos)) {
            foreach ($promos->getReferentilPromo() as $referentiel) {
                if ($referentiel->getId() == $ID) {
                    break;
                }
                $referentiel = null;
            }
            if (!empty($referentiel)) {
                foreach ($referentiel->getGrpCompetences() as $grc) {
                    foreach($grc->getCompetence() as $competence){
                        $competences[]=$competence;
                    } 
                }
                return $this->json($competences, 200, [],['groups'=>['promo_cmpt']]);
            }
        }
    }
    
    /**
     * @Route(
     * name="form_stat",
     * path="api/formateur/promo/{idp}/referentiels/{idr}/statistique/competence",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=Promo::class,
     * "_api_collection_operation_name"="form_stat",
     * "deserialize" = false
     * }
     * )
     */

    public function getCompetences(ReferentielRepository $repo,SerializerInterface $serializer,PromoRepository $promosRepository, $idp,$idr)
    {
        
        $promo = $promosRepository->find($idp);
        foreach($promo->getReferentilPromo() as $referentiel){
            foreach($referentiel->getGrpCompetences() as $grpc){
                
                foreach($grpc->getCompetence() as $competence){
                    $nb1=0;$nb2=0;$nb3=0;
                    foreach($competence->getCompetenceValides() as $compValid){
                        dd($compValid);
                        if ($compValid->getNiveau1() == true){
                            $nb1 += 1;
                        }
                        if ($compValid->getNiveau2() == true){
                            $nb2 += 1;
                        }
                        if ($compValid->getNiveau3() == true){
                            $nb3 += 1;
                        }
                   }
                   $tab[] = ["competence"=>$competence,"niveau 1"=>$nb1.' apprenant valide',"niveau 2"=>$nb2.' apprenant valide',"niveau 3"=>$nb3.' apprenant valide'];
                  
                }
               
                return $this->json($tab,200,[],["groups"=>"grp"]);
            }
        }
       
    }
    /**
     * @Route(
     *   name="apprenant_competences",
     *   path="api/apprenant/{id}/promo/{idp}/referentiel/{idr}/competences",
     *   methods={"GET"}
     * )
     */

    public function getReferentielIdCompetences(PromoRepository $promoRepo,CompetenceValideRepository $statRepo, ApprenantRepository $apprenantRepository,SerializerInterface $serializer,$id,$idp,$idr){
        $stats = $statRepo->findAll();
        foreach ($stats as $stat){
            $promo = $stat->getPromo();
            $apprenant = $stat->getApprenant();
            if(($promo->getId() == $idp) && ($apprenant->getId() == $id)){
                $competence = $stat->getCompetence();
                $competenceTab = $serializer->normalize($competence,'json',["groups"=>"competence:read"]);
                $tab[] = ["competence"=>$competenceTab];
            }
        }

        return $this->json($tab,200,[]);

    }
    /**
     * @Route(path="/api/apprenant/{id}/promo/{idp}/referentiel/{idr}/statistisques/briefs",
     *        name="apigetApprenantIdPromoIdReferentielIdStatistiquesBriefs",
     *        methods={"GET"}
     *)
     */
    public function getApprenantBriefs(ApprenantRepository $appRepo,$id,$idp,$idr,SerializerInterface $serializer){
        $apprenant = $appRepo->find($id);
        $groupes = $apprenant->getGroupes();
        foreach ($groupes as $groupe){
            if($groupe->getLibelle()=="GP"){
                if ($groupe->getPromo()->getId() == $idp){
                    $briefs = $groupe->getEtatBriefs();
                    $nbreAssigne=0;$nreValid=0;$nbreNonValid=0;$rendu=0;
                    foreach ($briefs as $brief){
                        $statut = $brief->getStatut();
                        if ($statut === "valide"){
                            $nreValid +=1;
                        }elseif ($statut ==="non valide"){
                            $nbreNonValid +=1;
                        }else{
                            $nbreAssigne +=1;
                        }
                        $rendu=$nbreAssigne+$nreValid+$nbreNonValid;
                    }
                }
            }
            $tab [] =["Apprenant"=>$apprenant,"Valide"=>$nreValid,"Non Valide"=>$nbreNonValid,"Assigne"=>$nbreAssigne,"rendu"=>$rendu];
        }
        return $this->json($tab,200,[],['groups'=>'brief_read']);
    }
    /**
     * @Route(
     *     name="get_deux_it",
     *     path="/api/apprenants/{id}/livrablepartiels/{id_d}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::putAppLiv",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_item_operation_name"="get_deux_it"
     *     }
     * )
     */

    public function putAppLiv(Request $request, EntityManagerInterface $manager, ApprenantRepository $apprenantRepository, LivrablePartielRepository $livrablePartielRepository, int $id, int $id_d)
    {
        if ( $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') ){
            $etatTab= json_decode($request->getContent(),true);
        
            $apprenant = $apprenantRepository->findOneBY(["id" => $id]);

            $livrapartiel = $livrablePartielRepository->findOneBY(["id" => $id_d]);

            if (!$apprenant) {
                return new JsonResponse("L'apprenant dont l'id=" . $id . "n'existe pas", Response::HTTP_CREATED, [], true);

            }
            if (!$livrapartiel) {
                return new JsonResponse("Le livrable dont l'id=" . $id_d . "n'existe pas", Response::HTTP_CREATED, [], true);
            }
            foreach ($apprenant->getApprenantLivrablePartiels() as $apl) {
            if ($apl->getLivrablePartiel()->getId()== $id_d){
                $apl->setEtat($etatTab['etat']);
            }
            }
            $manager->flush();
            return $this->json("Modification reussi");
        }else{
            return new Response (" pas d'acces");
        }
    }
    /**
     * @Route(
     * name="getComment",
     * path="/api/formateurs/livrablepartiels/{id}/commentaire",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=LivrablePartiel::class,
     * "_api_collection_operation_name"="getComment"
     * }
     * )
     */
    public function getCommentaire(Request $request, $id,LivrablePartielRepository $repoLv ,EntityManagerInterface $em)
    {
        $livrable=$repoLv->findOneBY(['id'=>$id]);
        if (!$livrable) {
            return new Response("Le livrable partiel  dont l'id=" . $id . " n'existe pas");
        }
        foreach($livrable->getApprenantLivrablePartiels() as $appLiv){
            foreach($appLiv->getFilDiscutions() as $disc){
               $tab[]=$disc;
            }
        }
        return $this->json($tab, 200, [],['groups'=>['all_comment']]);
    }
    /**
     * @Route(
     *     name="addCommentaire",
     *      path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *      methods={"POST"}
     * )
     */
    public function addCommentaire($id,CommentaireRepository $commentaireRepository,
    FilDiscutionRepository $filDiscution,LivrablePartielRepository $livrablePartiel,
    Request $request,EntityManagerInterface $manager,ApprenantLivrablePartielRepository $apprenantlLivrable,TokenStorageInterface $token)
    {
        $livrables= new LivrablePartiel();
        $livrable=$livrablePartiel->findOneBy(["id"=>$id]);
        if (empty($livrable)) {
            return $this->json(["message" => "l'id renseigner ne correspond a aucun livrable partiel "],Response::HTTP_FORBIDDEN);
        }
        $user=$token->getToken()->getUser();
        $livrableApprenant=$livrable->getApprenantLivrablePartiels();
        $data=json_decode($request->getContent(),true);
        
        $commentaire= new commentaire();
        $commentaire->setDescription($data['description']);
        $commentaire->setCreateAt(new \DateTime());
        if ($user instanceof Formateur){
            $commentaire->setFormateur($user);
        }
        $manager->persist($commentaire);
        $fil=new filDiscution();
        $fil->setCommentaire($commentaire);
        if ($user instanceof Apprenant){
        $fil->setApprenantLivravlePartiel($livrableApprenant);
        }
        $manager->persist($fil);
        $manager->flush(); 
        return $this->json("success",Response::HTTP_OK);
    }
    /**
     * @Route(
     *     name="modiflivrable",
     *      path="/api/formateurs/promo{idp}/briefs/{idb}/livrablePartiels",
     *      methods={"PUT"}
     * )
     */
    public function editLivrablePartielByFormateur(Request $request,$idp, $idb, BriefMaPromoRepository $repoBriefMaPromo,BriefRepository $repoBrief,PromoRepository $repoPromo,LivrablePartielRepository $repoLV,EntityManagerInterface $em)
    {
        if ( $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_FORMATEUR') ) {
        $json = json_decode($request->getContent());
        $promo=$repoPromo->find($idp);
        $brief=$repoBrief->find($idb);
        $livrableP=$repoLV->findAll();
        foreach($promo->getBriefMaPromos() as $briefdupromo){
            if ($briefdupromo->getBrief()->getId()==$brief->getId() && $briefdupromo->getPromo()->getId()==$promo->getId()) {
                if($json->ajout==true){
                    $livrablePartiel=new LivrablePartiel();
                    $livrablePartiel->setDelaiAt(new \DateTime())
                                    ->setLibelle($json->libelle)
                                    ->setDescription($json->description)
                                    ->setType($json->type)
                                    ->setDeleted($json->deleted)
                                    ->setBriefMaPromo($briefdupromo);
                    $em->persist($livrablePartiel);
                }
                foreach($livrableP as $value){
                    if($value->getId()==($json->deletedId)){
                        $value->setDeleted(true);
                    }
                }
                $em->flush();
                return $this->json("success",Response::HTTP_OK);
            }
        }        
        return $this->json("brief ou promo inexistant");
    }
}
        
}