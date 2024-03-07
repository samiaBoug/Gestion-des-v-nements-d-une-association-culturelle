<!-- code PHP -->
<?php
  //session
  if (!isset($_SESSION)) {
   session_start();
  }
  //button connexion/deconnexion
  $connexion = "";
  if(isset($_SESSION['idUtilisateur'])){
    $idUtilisateur=$_SESSION['idUtilisateur'] ;
    $headerProfil = "profil.php?id=$idUtilisateur";
    $headerHistory = "history.php?id=$idUtilisateur";
    $connexion = "Deconnexion";
  }else{
    $connexion = "Connexion";
    $headerProfil =$headerHistory= "login.php";
  }

  if(isset($_POST['Connexion'])){
    header('location:login.php');
  }elseif(isset($_POST['Deconnexion'])){
    session_destroy();
    header('location:login.php');
  }
?>

<!-- HTML -->
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evenements</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <header class="navbar navbar-expand-xxl bg-body-tertiary">
    <div class="container-fluid header">
        <a href="index.php" class="navbar-img"><img src="img/FARHA.png" alt="logo-gif" height="60px"></a>
        <a class="navbar-brand" href="index.php">FARHA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $headerProfil ?>">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $headerHistory ?>">Vos Achats</a>
                </li>
                <li class="nav-item">
                    <form action="" method="POST">
                        <button type="submit" class="btn btn-primary connexion " name="<?php echo $connexion ?>"><?php echo $connexion ?></button>
                    </form>
                </li>
            </ul>
        </div>
      </div>
    </header>