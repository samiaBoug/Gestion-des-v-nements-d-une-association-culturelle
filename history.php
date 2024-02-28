<?php
include('include/header.php');
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}
$idUtilisateur = $_SESSION['idUtilisateur'];

$sqlFacturesUtilisateur = $conn->query("select * from utilisateur inner join facture on utilisateur.idUtilisateur=facture.idUtilisateur where utilisateur.idUtilisateur='$idUtilisateur' ");
$facturesUtilisateur = $sqlFacturesUtilisateur->fetchAll(PDO::FETCH_ASSOC);

?>

<section>
   <h2>Bonjour <?php echo $facturesUtilisateur[0]['nom'] ." ".$facturesUtilisateur[0]['prenom'] ?> !</h2>
   <h3> Vos Achats : </h3>
    <h4>historiques des factures : </h4>
   <table class="table">
      <tr>
         <th>Id facture</th>
         <th>Date de facture</th>
         <th>titre d'evenement</th>
         <th>nombre de billets</th>
         <th>montant totale</th> 
      </tr>
      <?php 
      foreach($facturesUtilisateur as $facture){
         $idVersion = $facture['idVersion'];
         $sqlEvent = $conn->query("select * from version inner join evenement on version.idEvenement=evenement.idEvenement where version.idVersion='$idVersion'");
         $event = $sqlEvent->fetch(PDO::FETCH_ASSOC);
         $idFacture = $facture['idFacture'];
         $sqlNbreBillet = $conn->query("select count(codeBillet) from billet where idFacture='$idFacture'");
         $nbreBillets = $sqlNbreBillet->fetch(PDO::FETCH_ASSOC);
         // montant à payer : evenement tarifNormal * billet de type normale + tarif Reduit * nbre billet de type reduit
         $sqlBilletNormal = $conn->query("select count(codeBillet) from billet where typeBillet='normal' and idFacture='$idFacture'");
         $billetsNormal = $sqlBilletNormal->fetch(PDO::FETCH_ASSOC);
         $nbreBilletNormal = $billetsNormal['count(codeBillet)'];

         $sqlBilletReduit = $conn->query("select count(codeBillet) from billet  where typeBillet='Reduit' and idFacture='$idFacture'");
         $billetsReduit = $sqlBilletReduit->fetch(PDO::FETCH_ASSOC);
         $nbreBilletReduit = $billetsReduit['count(codeBillet)'];

         $tarifNormal = $event['tarifnormal'];
         $tarifReduit = $event['tarifReduit'];
         $totale = ($nbreBilletNormal * $tarifNormal) + ($nbreBilletReduit * $tarifReduit);


      echo '<tr>';
      echo '<td>'.$facture['idFacture'].'</td>';
      echo '<td>'.$facture['dateFacture'].'</td>';
      echo '<td>'.$event['titre'].'</td>';
      echo '<td>'.$nbreBillets['count(codeBillet)'].'</td>';
      echo '<td>'.$totale.'</td>';
      echo '</tr>';
      }
      ?>
   </table>
   <h4>historiques de billets :</h4>
</section>
<?php
include('include/footer.php')
?>