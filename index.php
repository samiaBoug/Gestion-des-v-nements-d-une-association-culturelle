<?php 
  //include connexion 
  include("include/conn.php");
  $currentDate = date('Y-m-d H:i:s') ;

  $selectEvents = "SELECT * FROM evenement INNER JOIN version ON evenement.idEvenement=version.idEvenement";
  // recherche par titre  : 
  if(isset($_POST['recherche'])){
    $titreChercher = $_POST['titreChercher'];
    $stmSearch = $conn->prepare(" $selectEvents
                                  WHERE evenement.titre LIKE '%$titreChercher%'");
    $stmSearch->execute();
    $evenements =$stmSearch->fetchAll(PDO::FETCH_ASSOC);
    echo '<script>window.location.hash = "#listEvents";</script>';
 
  }elseif(isset($_POST['filtreDate'])){
    // recherche par Date: 
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $stm = $conn->prepare("$selectEvents
                           WHERE version.dateEvenement <= :dateFin AND version.dateEvenement >= :dateDebut");

    $stm->bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $stm->bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $stm->execute();
    $evenements = $stm->fetchAll(PDO::FETCH_ASSOC);

  }elseif(isset($_POST['filtreCategorie'])){
    // recherche par Categorie: 
    $categorie = $_POST['categorieFiltre'];
    $stm = $conn->prepare("$selectEvents
                           WHERE evenement.categorie like :categorie");
    $stm->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $stm->execute();
    $evenements = $stm->fetchAll(PDO::FETCH_ASSOC);
    echo '<script>window.location.hash = "#listEvents";</script>';

  }else {
    $sqlEvenements = $conn->prepare("select * from evenement inner join version on evenement.idEvenement = version.idEvenement order by dateEvenement ");
    $sqlEvenements->execute();
    $evenements = $sqlEvenements->fetchAll(PDO::FETCH_ASSOC);
  }

  // options de select categories
  $sqlCategories = $conn->prepare("select distinct categorie from evenement");
  $sqlCategories->execute();
  $categories = $sqlCategories->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- include header -->
<?php include('include/header.php')?>
  <!-- nav de filtre -->
  <nav class="navbar d-flex ">
    <div class="container-fluid d-flex">
      <div class="d-flex" id="">
        <!-- form filtre categorie-->
        <form action="" class="d-flex formFiltre" method="POST" >
          <select name="categorieFiltre" id="" style="border: none; background-color: #cfa9d8;">
            <option value="">Categories</option>
            <?php
              foreach($categories as $categorie){
                echo '<option value="'.$categorie['categorie'].'">'.$categorie['categorie'].'</option>';
              }
            ?>
          </select>
          <input type="submit" name="filtreCategorie" value="OK" style="margin-left:10px" class="btn btn-outline-success">
        </form>
        <!-- form filtre date -->
        <form action="" method="POST" class="formFiltre formFiltre" style="margin-right: 40px; margin-left:-43px;">
          <label for="">Date debut :</label>
          <input type="date" name="dateDebut" style="border: none; background-color: #cfa9d8;">
          <label for="">Date fin :</label>
          <input type="date" name="dateFin" style="border: none; background-color: #cfa9d8; ;">
          <input type="submit" name="filtreDate" value="OK" style="margin-left:10px" class="btn btn-outline-success">
        </form>
        <!-- form filtre categorie -->
        <form class="d-flex" role="search" method="POST" action="" class="formFiltre">
          <input class="form-control me-2" type="search" placeholder="Recherche par titre" aria-label="Search" name="titreChercher">
          <button class="btn btn-outline-success" type="submit" name="recherche">Recherche</button>
        </form>
      </div>
    </div>
  </nav>
  <!-- Contenue -->
  <section class="container custom-container ">
      <div id="carousel" class="carousel slide" style="margin-bottom:20px">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/event.jpg" class="d-block w-100" alt="">
    </div>
    
    <?php
    foreach ($evenements as $evenement) {
      echo '<div class="carousel-item">';
      echo '<img src="'.$evenement['img'].'" class="d-block w-100" alt="">';
      echo '</div>';
    }
    ?>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>

      <div class="row" id="listEvents">
        <?php
          foreach ($evenements as $evenement) {
            $idVersion = $evenement['idVersion'];
            $idEvenement = $evenement['idEvenement'];
            $dateEvenement = $evenement['dateEvenement'];
            if($currentDate< $dateEvenement){
                $sqlVersionDate = $conn-> prepare("select dateEvenement from version where idEvenement = :idEvenement ");
                $sqlVersionDate->bindParam(':idEvenement', $idEvenement);
                $sqlVersionDate->execute();
                $versionsDate = $sqlVersionDate ->fetchAll(PDO::FETCH_ASSOC);
                echo '<div class="card col-4 evenement" style="margin= 10px" >';
                echo '<img src="' . $evenement['img'] . '" class="card-img" alt="...">';
                echo '<div class="card-img">';
                echo '<h6 class="card-title">'.$evenement['categorie'] .'</h6>';
                echo '<h4 class="card-title">'.$evenement['titre'] .'</h4>';
                echo '<p class="card-text"> Rendez-vous le : ' . $evenement['dateEvenement'] . '</p>';
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
    

  </section>
<!-- include header -->

<?php
include('include/footer.php');
?>