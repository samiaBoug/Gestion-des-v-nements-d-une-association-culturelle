
<?php
if (!isset($_SESSION)) {
  session_start();
}
$connexion = "";
if(isset($_SESSION['idUtilisateur'])){
  $idUtilisateur=$_SESSION['idUtilisateur'] ;
  $headerProfil = "profil.php?id=$idUtilisateur";
  $headerHistory = "history.php?id=$idUtilisateur";
  $connexion = "Log-out";
}else{
  $connexion = "Connexion";
  $headerProfil =$headerHistory= "login.php";
}
if(isset($_POST['Connexion'])){
  header('location:login.php');
}elseif(isset($_POST['Log-out'])){
  session_destroy();
  header('location:login.php');
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
    <header class="navbar navbar-expand-lg bg-body-tertiary">
       
      <div class="navInclude">

      </div>
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">FARHA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <form action="" method="POST">
          <button type="submit" class="btn btn-primary" name="<?php echo $connexion ?>"><?php echo $connexion ?></button>
           </form>
        </li>

      </ul>
  
    </div>
  </div>

    </header>
     <div >
    <ul class="nav flex-column" id="verticNav">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $headerProfil ?>">Profil</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $headerHistory ?>">Vos Achats</a>
  </li>
 
</ul>
  </div>