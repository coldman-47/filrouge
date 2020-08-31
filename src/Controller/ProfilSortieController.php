<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\ProfilSortie;
use App\Repository\PromoRepository;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
    /**
     * @Route(
     * "api/admin/promo/{id}/profilsorties", 
     * name="profil_sorties_by_id",
     * methods={"GET"},
     * defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "profil_sorties_by_id"
     *  }
     * )
     */
    public function showApprenantPromoByProfilSortie($id,PromoRepository $repo_promo)
    {
        $promo = $repo_promo->find($id);
        if(!empty($promo)){
            $profilSorties = [];
            foreach ($promo->getGroupes() as $groupe) {
                foreach ($groupe->getApprenant() as $apprenant){
                    $PS = $apprenant->getProfilSorties();
                    foreach ($PS as $ps) {
                        if(!in_array($ps,$profilSorties)){
                            $profilSorties[] = $ps;
                        }
                    }
                }
            }
            return $this->json($profilSorties, 200);
        }else{
            return new Response("la promo n'existe pas");
        }
        
    }

     /**
     * @Route(
     * "api/admin/promo/{id}/profilsortie/{id1}", 
     * name="profil_sortie_by_id",
     * methods={"GET"},
     * defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "profil_sortie_by_id"
     *  }
     * )
     */
    public function showApprenantPromoByProfilSortieById($id, $id1, PromoRepository $repo_promo, ProfilSortieRepository $repo_profil_sortie)
    {
        //trouve identifiant du promo donné
        $promo = $repo_promo->find($id);
        $profil = $repo_profil_sortie->find($id1);
        if(!empty($promo)){
            $profilSorties = [];
            foreach ($promo->getGroupes() as $groupe) {
                foreach ($groupe->getApprenant() as $apprenant){
                    foreach ($apprenant->getProfilSorties() as $ps) {
                        
                        if($ps->getId() == $id1){

                            $profilSorties = $profil;
                            
                        }          
                    }
                }
            }
        
            return $this->json($profilSorties, 200);
        }else{
            return new Response("la promo n'existe pas");
        }
    }
}
