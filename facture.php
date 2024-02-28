<?php
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}
if(isset($_GET['id'])){
    if(isset($_SESSION['idUtilisateur'])){
       $idFacture = $_GET['id'];
       $idUtilisateur = $_SESSION['idUtilisateur'];
        $sqlFacture = $conn->query("select * from facture inner join utilisateur on facture.idUtilisateur=utilisateur.idUtilisateur where facture.idFacture='$idFacture' and utilisateur.idUtilisateur='$idUtilisateur'");
        $facture = $sqlFacture->fetch(PDO::FETCH_ASSOC);

        $sqlFactureEvent = $conn->query("select version.idVersion from facture inner join version on facture.idVersion=version.idVersion where facture.idFacture='$idFacture'");
        $factureEvenet = $sqlFactureEvent->fetch(PDO::FETCH_ASSOC);
        $idVersion = $factureEvenet['idVersion'];

        $sqlEvent = $conn->query("select * from evenement inner join version on evenement.idEvenement=version.idEvenement where version.idVersion='$idVersion'");
        $event = $sqlEvent->fetch(PDO::FETCH_ASSOC);

        $sqlBilletsNormal = $conn->query("select count(codeBillet) from billet where idFacture='$idFacture' and typeBillet='normal'");
        $nmbreBilletNormale = $sqlBilletsNormal->fetch(PDO::FETCH_ASSOC);

        $sqlBilletsReduit = $conn->query("select count(codeBillet) from billet where idFacture='$idFacture' and typeBillet='reduit'");
        $nmbreBilletReduit = $sqlBilletsReduit->fetch(PDO::FETCH_ASSOC);

        $sqlBillets = $conn->query("select * from billet inner join facture on billet.idFacture=facture.idFacture where facture.idFacture='$idFacture'");
        $billets = $sqlBillets->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
<?php include('include/header.php') ?>
<section>
<h1>La facture </h1>
<div class="card"
<div>
    <h2>ASSOCIATION FARHA</h2>
    <h6>center culturel farha Tanger</h6>
</div>
<div>
   <p> client : <?php echo $facture['nom'] ." ".$facture['prenom'] ?></p>
   <p>Adresse email :  <?php echo $facture['email'] ?> </p>
</div>
<div>
    <h5> <?php echo $event['titre']  ?></h5>
    <h6> Le <?php echo $event['dateEvenement'] ?></h6>
</div>
<div>
    FACTURE#<?php echo $facture['idFacture'] ?>
</div>
<table class="table">
<tr>
    <th>Tarif</th>
    <th>Prix</th>
    <th>Qte</th>
    <th>Total</th>
</tr>
<tr>
    <td>Normal</td>
    <td><?php echo $event['tarifnormal']?></td>  
    <td><?php echo $nmbreBilletNormale['count(codeBillet)']; ?></td>
    <td><?php echo  $event['tarifnormal']*$nmbreBilletNormale['count(codeBillet)']  ?> DH</td>
</tr>
<tr>
    <td>Reduit</td>
    <td><?php echo $event['tarifReduit'] ?></td>
    <td><?php echo $nmbreBilletReduit['count(codeBillet)'];  ?> </td>
    <td><?php echo  $event['tarifReduit'] * $nmbreBilletReduit['count(codeBillet)'] ?> DH</td>

</tr>
</table>
<div>Totale à payer : <?php echo ( $event['tarifnormal']*$nmbreBilletNormale['count(codeBillet)'] )+ ($event['tarifReduit'] * $nmbreBilletReduit['count(codeBillet)'] ) ?> DH</div>
<div>MERCI!</div>
</div>
<h1>Les billets</h1>
<?php 
foreach($billets as $billet){
    $tarif = "";
    if($billet['typeBillet'] === "normal"){
        $tarif = $event['tarifnormal'];
    }elseif($billet['typeBillet'] === "Reduit"){
        $tarif = $event['tarifReduit'];
    }
echo '<div>';
echo 'Numéro de ticket: #'.$billet['codeBillet'];
   echo '</div>';

echo '<div class="card">';
echo '<div>';
echo '<h2>'.$event['titre'].'</h2>';
echo '<h4>'.$event['dateEvenement'].'</h4>';
    echo '</div>';
echo '<div>';
echo '<h2>ASSOCIATION FARHA</h2>';
echo '<h6>tarif : '.$tarif. 'DH </h6>';
echo '<h6>Addresse: Centre culturel Farha, Tanger</h6>';
    echo '</div>';

echo '<div>';
echo '<img src="img/codbar.jpg" width="60px" height="20px">';
echo '<h5>Place :'.$billet['numPlace'].'<?php ?></h5>';
echo '<h5>Salle :'.$event['numSalle'].'<?php ?></h5>';
    echo '</div>';

    echo '</div>';




}


?>




<!-- copier -->
 


</section>

<?php include('include/footer.php') ?>

