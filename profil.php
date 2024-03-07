<?php
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}
if (isset($_SESSION['idUtilisateur'])) {
  $idUtilisateur = $_SESSION['idUtilisateur'];
  $sql = $conn->query("select * from utilisateur where idUtilisateur = '$idUtilisateur' ");
  $idUtilisateur = $sql->fetch(PDO::FETCH_ASSOC);
 
  if(stripos($idUtilisateur['email'], 'admin') !==false){
    header('location:admin.php');
    exit();
  }
}

?>

<?php include('include/header.php') ?>

<section class="container container-fluid">
<div class="card">
  <div class="card-header">
    Profil
  </div>
  <div class="card-body">
    <h4><?php
    if(isset($_SESSION['idUtilisateur'])){
        $idUtilisateur = $_SESSION['idUtilisateur'];
         $sql = $conn->query("select * from utilisateur where idUtilisateur = '$idUtilisateur' ");
        $idUtilisateur = $sql->fetch(PDO::FETCH_ASSOC);
        echo "Utilisateur : ".$idUtilisateur['nom'] . " " . $idUtilisateur['prenom'] ."<br>";
        echo "Adresse mail :".$idUtilisateur['email'];
    }
    else{
        header('location:login.php');
    }
    
    ?>
    </h4>
    <form action="" method="POST">
    <a href="settings.php">Modifier le profil</a>

    </form>

  </div>
</div>
</section>

<?php include('include/footer.php') ?>
