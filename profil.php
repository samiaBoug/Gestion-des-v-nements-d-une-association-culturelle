<?php
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
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
    if(isset($_GET['id'])){
        $idUtilisateur = $_GET['id'];
         $sql = $conn->query("select * from utilisateur where idUtilisateur = '$idUtilisateur' ");
        $idUtilisateur = $sql->fetch(PDO::FETCH_ASSOC);
        echo $idUtilisateur['nom'] . " " . $idUtilisateur['prenom'];
    }
    else{
        header('location:login.php');
    }
    
    ?>
    </h4>
    <form action="" method="POST">
    <a href="settings.php?id=<?= $_GET['id'] ?>">Modifier le profil</a>
    </form>

  </div>
</div>
</section>

<?php include('include/footer.php') ?>
