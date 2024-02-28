
<?php 
// session
if(!isset($_SESSION)){
  session_start();
}
//include connexion 
include("include/conn.php");
$currentDate = date('Y-m-d H:i:s') ;

// recherche par titre  : 
  if(isset($_POST['recherche'])){
  $titreChercher = $_POST['titreChercher'];
  $stmSearch = $conn->prepare("SELECT * FROM evenement INNER JOIN version ON evenement.idEvenement=version.idEvenement WHERE evenement.titre LIKE '%$titreChercher%'");
  $stmSearch->execute();
  $evenements =$stmSearch->fetchAll(PDO::FETCH_ASSOC);
 
}elseif(isset($_POST['filtreDate'])){
  $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $stm = $conn->prepare("SELECT * FROM evenement
                           INNER JOIN version ON version.idEvenement = evenement.idEvenement
                           WHERE version.dateEvenement <= :dateFin AND version.dateEvenement >= :dateDebut");

    $stm->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $stm->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $stm->execute();
    $evenements = $stm->fetchAll(PDO::FETCH_ASSOC);

}elseif(isset($_POST['filtreCategorie'])){
  $categorie = $_POST['categorieFiltre'];
   $stm = $conn->prepare("SELECT * FROM evenement
                           INNER JOIN version ON version.idEvenement = evenement.idEvenement
                           WHERE evenement.categorie like :categorie");

    $stm->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $stm->execute();
    $evenements = $stm->fetchAll(PDO::FETCH_ASSOC);

}
 else {


  $sqlEvenements = $conn->query("select * from evenement inner join version on evenement.idEvenement = version.idEvenement order by dateEvenement ");
  $evenements = $sqlEvenements->fetchAll(PDO::FETCH_ASSOC);
}
  

    // filtre par categories
  $sqlCategories = $conn->query("select distinct categorie from evenement");
  $categories = $sqlCategories->fetchAll(PDO::FETCH_ASSOC);



?>



  <?php include('include/header.php')?>
  

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="navInclude">

      </div>
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <!-- form filtre categorie-->
      <form action="" class="d-flex" method="POST">
        <select name="categorieFiltre" id="">
          <option value="">Categories</option>
          <?php
          foreach($categories as $categorie){
            echo '<option value="'.$categorie['categorie'].'">'.$categorie['categorie'].'</option>';
            
          }
          ?>
        </select>
        <input type="submit" name="filtreCategorie">
      </form>
      <!-- form filtre date -->
      <form action="" method="POST">
        <label for="">Date debut</label>
        <input type="date" name="dateDebut">
        <label for="">Date fin</label>
        <input type="date" name="dateFin" >
        <input type="submit" name="filtreDate">
      </form>
      <form class="d-flex" role="search" method="POST" action="">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="titreChercher">
        <button class="btn btn-outline-success" type="submit" name="recherche">Search</button>
      </form>
    </div>
  </div>
</nav>

<section class="container container-fluid">
  
  <div>
  <div id="" class="carousel slide">
  <div class="carousel-inner ">
    <div class="carousel-item active">
      <img src="img/event.webp" class="d-block w-100" alt="...">
    </div>
    <?php
    foreach ($evenements as $evenement) {
      echo '<div class="carousel-item ">';
      echo '<img src="'.$evenement['img'].'" class="d-block w-100" alt="">';
      echo '</div>';
    }
    ?>
    
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div class="carte">
  


    <?php
 
    foreach ($evenements as $evenement) {
      $idVersion = $evenement['idVersion'];
      $idEvenement = $evenement['idEvenement'];
      $dateEvenement = $evenement['dateEvenement'];
      if($currentDate< $dateEvenement){
        $sqlVersionDate = $conn-> query("select dateEvenement from version where idEvenement = '$idEvenement' ");
         $versionsDate = $sqlVersionDate ->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<div class="card" style="width: 18rem;">';
        echo '<img src="' . $evenement['img'] . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h6 class="card-title">'.$evenement['categorie'] .'</h6>';
        echo '<h4 class="card-title">'.$evenement['titre'] .'</h4>';
       
        // date de l'evenement 
        echo '<p class="card-text"> Rendez-vous le : ' . $evenement['dateEvenement'] . '</p>';
          // boutton j'achète :
          // si la capacité salle <= nombre billet acheter d'un evenement
          $numSalle= $evenement['numSalle'];
        $sqlCapaciteSalle = $conn->query("select capacite from salle where numSalle='$numSalle'");
        $capacite = $sqlCapaciteSalle->fetch(PDO::FETCH_ASSOC);
          //coun(codeBillet)
        $idVersion = $evenement['idVersion'];
        $sqlNumBilletAcheter = $conn->query("select count(codeBillet) from billet inner join facture on billet.idFacture=facture.idFacture where facture.idVersion='$idVersion'");
        $nbreBillet = $sqlNumBilletAcheter->fetch(PDO::FETCH_ASSOC);
        if($capacite['capacite'] <= $nbreBillet['count(codeBillet)']){
          $boutton = "Sold Out";
        }else{
          $boutton = "J'achète";
        }

      
        echo '<a href="details.php?id=' . $evenement['idVersion'] . '" class="btn btn-primary">'.$boutton.'</a>';
        echo ' </div>';
        echo ' </div> ';
     }
    }  
    
    ?>
    </div>
    </div>

</section>

<?php
include('include/footer.php');
?>