
<?php 
$currentDate = date('Y-m-d H:i:s') ;
include("include/conn.php");
try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlEvenements = $conn-> query ("select * from evenement inner join version on evenement.idEvenement = version.idEvenement order by dateEvenement ");
    $evenements = $sqlEvenements ->fetchAll(PDO::FETCH_ASSOC);


}
catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();

}

?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evenements</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FARHA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Sign in</a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="#">Sign Up</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<section class="container container-fluid">
    <?php
   
    foreach ($evenements as $evenement) {

      $idEvenement = $evenement['idEvenement'];
        $sqlVersionDate = $conn-> query("select dateEvenement from version where idEvenement = '$idEvenement' ");
         $versionsDate = $sqlVersionDate ->fetchAll(PDO::FETCH_ASSOC);
        echo '<div class="card" style="width: 18rem;">';
        echo '<img src="' . $evenement['img'] . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">'.$evenement['titre'] .'</h5>';
        // date de l'evenement 
        echo '<p class="card-text">' . $evenement['dateEvenement'] . '</p>';
      

          echo '<a href="#" class="btn btn-primary">Réservez vos billets </a>
                 </div>
                </div> ';

     
    
      
    }
    
    ?>
    

</section>
<footer>

</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>