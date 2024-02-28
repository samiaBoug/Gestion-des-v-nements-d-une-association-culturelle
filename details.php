<?php
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}
if(isset($_GET['id'])){
    $idVersion = $_GET['id'];
    //recuperer data
    $sqldetails = $conn->query("select * from version inner join evenement on version.idEvenement=evenement.idEvenement where version.idVersion = '$idVersion'");
    $details = $sqldetails->fetch(PDO::FETCH_ASSOC);
    // formule 
    if(isset($_POST['buyTicket'])){
        $errors = [
            'connexion'=> "",
            'nbreBillets'=>""

        ];
        // verifier si l'utilisateur est connecté :
            if(isset($_SESSION['idUtilisateur'])){
            // verifier si les billets selectionner disponible
            $numSalle = $details['numSalle'];
            $sqlCapacitéSalle = $conn->query("select capacite from salle inner join version on salle.numSalle=version.numSalle where salle.numSalle = '$numSalle' ");
            $capaciteSalles = $sqlCapacitéSalle->fetch(PDO::FETCH_ASSOC);
            $capaciteSalles['capacite'];
             
                // selectionner le nombre des billets acheter dans un version evenement : 
            $sqlNumBilletAchete = $conn->query("select count(codeBillet) from billet inner join facture on billet.idFacture=facture.idFacture  where facture.idVersion = '$idVersion'");
            $nombreBilletAchete = $sqlNumBilletAchete->fetch(PDO::FETCH_ASSOC);

            $nbreBilletSelectionner = $_POST['nmbreBilletNormal'] + $_POST['nmbreBilletReduit'];

            if (($capaciteSalles['capacite'] - $nombreBilletAchete['count(codeBillet)']) >= $nbreBilletSelectionner) {
           //ajouter ligne dans table facture : 
        
            $idUtilisateur = $_SESSION['idUtilisateur'];
            $dateFacture = date('Y-m-d H:i:s');
            $sqlAjouterFacture = $conn->prepare("INSERT INTO facture ( dateFacture, idUtilisateur, idVersion)
                                               VALUES (:dateFacture, :idUtilisateur, :idVersion);");
                $sqlAjouterFacture->bindParam(':dateFacture', $dateFacture);
                $sqlAjouterFacture->bindParam(':idUtilisateur', $idUtilisateur);
                $sqlAjouterFacture->bindParam(':idVersion', $idVersion);
                $sqlAjouterFacture->execute();
            
            
        //ajouter ligne dans table billet
        //    recuperer id de la facture ajouter :
        $lastInsertedId = $conn->lastInsertId();
            $sqlSelectFacture = $conn->query("SELECT idFacture FROM facture WHERE idFacture = $lastInsertedId");
            $factureAjouter = $sqlSelectFacture->fetch(PDO::FETCH_ASSOC);
            $idFacture = $factureAjouter['idFacture'];
        //ajouter billets de type normal 
        for($i=0;$i<$_POST['nmbreBilletNormal'] ; $i++ ){
            //place 
            $sqlNumBilletAchete = $conn->query("select count(codeBillet) from billet inner join facture  where facture.idVersion = '$idVersion'");
            $nombreBilletAchete = $sqlNumBilletAchete->fetch(PDO::FETCH_ASSOC);
            $Place = $capaciteSalles['capacite'] - $nombreBilletAchete['count(codeBillet)'] ;

        
        $type = "normal";
          $sqlAjouterBillet = $conn->prepare("INSERT INTO billet ( typeBillet, numplace, idFacture)
                                              VALUES (:normal, :Place, :idFacture)");
          $sqlAjouterBillet->bindParam(':normal', $type);
          $sqlAjouterBillet->bindParam(':Place', $Place);
          $sqlAjouterBillet->bindParam(':idFacture', $idFacture);
          $sqlAjouterBillet->execute();
        
         };
        //ajouter billets de type reduit 
          for($i=0;$i<$_POST['nmbreBilletReduit'] ; $i++ ){
            //place 
            $sqlNumBilletAchete = $conn->query("select count(codeBillet) from billet inner join facture  where facture.idVersion = '$idVersion'");
            $nombreBilletAchete = $sqlNumBilletAchete->fetch(PDO::FETCH_ASSOC);
            $Place = $capaciteSalles['capacite'] - $nombreBilletAchete['count(codeBillet)'] ;
                    $type = "Reduit";
            $sqlAjouterBillet = $conn->prepare("insert into billet ( typeBillet, numplace, idFacture) values (:type, :place , :idFacture)");
                    $sqlAjouterBillet->bindParam(':type', $type);
                    $sqlAjouterBillet->bindParam(':place', $Place);
                    $sqlAjouterBillet->bindParam(':idFacture', $idFacture);
                    $sqlAjouterBillet->execute();

         };


         //affiche facture + afficher billets 
               
                header("location:facture.php?id=$idFacture");
                
            }else{
                
            $errors['nbreBillets'] = "Nombre de billets sélectionner sont épuisés !";
            }
    } else{
        // vous devez connécter
            $errors['connexion'] = "Vous devez d'abord vous connecter.";
           
    }     
            }
    


}


?>
<?php include('include/header.php') ?>
<section class="container">

<div class="card" style="width: 18rem;">
  <img src="<?php if (!empty($details)) {
      echo $details['img']; } ?>" class="card-img-top" alt="">
  <div class="card-body">
    <h1 class="card-title">Détails de l'évenement </h1>
    <h2 class=""><?php if (!empty($details)) {
        echo $details['titre']; } ?></h2>
    <h3 class=""> <?php if (!empty($details)) {
        echo $details['categorie'];
    } ?> </h3>
    <p class="card-text"><?php if (!empty($details)) {
        echo $details['description'];
    } ?></p>
    <h5>date : <?php if (!empty($details)) {
        echo $details['dateEvenement'];
    } ?>  </h5>
    <h6>num de salle : <?php if (!empty($details)) {
        echo $details['numSalle'];
    } ?> </h6>
    <div class="mb-3">
        <span class="erreurs"><?php if (!empty($errors['connexion'])) {
            echo $errors['connexion'];} ?></span>
            <span class="erreurs"><?php if (!empty($errors['nbreBillets'])) {
            echo $errors['nbreBillets'];} ?></span>
     <form action="details.php?id=<?php  echo $idVersion ?>" method="post">
      <label for="" class="form-label">Tarif normal : <?php echo $details['tarifnormal'] ?> MAD </label>
      <label for="" class="form-label">selectionner les billets</label>
      <input type="number" value=1 min=1 class="form-control" name="nmbreBilletNormal">

    <label for="" class="form-label">Tarif réduit : <?php echo $details['tarifReduit'] ?> MAD </label>
      <label for="" class="form-label">selectionner les billets</label>
      <input type="number" value=1 min=1 class="form-control" name="nmbreBilletReduit">
      <input class="form-control" type="submit" name="buyTicket" value="Acheter un billet" class="btn btn-primary">
     </form>
    </div>
  </div>
</div>




</section>

<?php include('include/footer.php') ?>
