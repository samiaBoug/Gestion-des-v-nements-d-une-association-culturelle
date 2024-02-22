
<?php 
$currentDate = date('Y-m-d H:i:s') ;
include("include/conn.php");

    $sqlEvenements = $conn-> query ("select * from evenement inner join version on evenement.idEvenement = version.idEvenement order by dateEvenement ");
    $evenements = $sqlEvenements ->fetchAll(PDO::FETCH_ASSOC);


    // filtre par categories
  $sqlCategories = $conn->query("select distinct categorie from evenement");
  $categories = $sqlCategories->fetchAll(PDO::FETCH_ASSOC);
  
  // filtre par date

  // recherche par titre
  $sqlTitres = $conn->query("select distinct categorie from evenement");
  $titres = $sqlTitres->fetchAll(PDO::FETCH_ASSOC);
  if(isset($_POST['recherhce']));
  

?>



  <?php include('include/header.php')?>
  

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="navInclude">

      </div>
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- form filtre categorie-->
      <form action="" class="d-flex">
        <select name="categorieFiltre" id="">
          <option value="">Categories</option>
          <?php
          foreach($categories as $categorie){
            echo '<option value="'.$categorie.'">'.$categorie['categorie'].'</option>';
            
          }
          ?>
        </select>
      </form>
      <!-- form filtre date -->
      <form action="">
        <label for="">Date debut</label>
        <input type="date" name="dateDebut">
        <label for="">Date fin</label>
        <input type="date" name="dateFin" >
        <input type="submit" name="filtreDate">
      </form>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" name="recherche">Search</button>
      </form>
    </div>
  </div>
</nav>

<section class="container container-fluid">
  <div >
    <ul class="nav flex-column" id="verticNav">
  <li class="nav-item">
    <a class="nav-link" href="#">Profil</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Vos Achats</a>
  </li>
 
</ul>
  </div>
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

      $idEvenement = $evenement['idEvenement'];
        $sqlVersionDate = $conn-> query("select dateEvenement from version where idEvenement = '$idEvenement' ");
         $versionsDate = $sqlVersionDate ->fetchAll(PDO::FETCH_ASSOC);
        echo '<div class="card" style="width: 18rem;">';
        echo '<img src="' . $evenement['img'] . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h6 class="card-title">'.$evenement['categorie'] .'</h6>';
        echo '<h4 class="card-title">'.$evenement['titre'] .'</h4>';
        // date de l'evenement 
        echo '<p class="card-text"> Rendez-vous le : ' . $evenement['dateEvenement'] . '</p>';
      

          echo '<a href="#" class="btn btn-primary">J\'achète </a>
                 </div>
                </div> ';

     
    
      
    }
    
    ?>
    </div>
    </div>

</section>

<?php
include('include/footer.php');
?>