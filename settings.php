<?php 
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}

$errors=[
    'nom' => '',
    'prenom' => '',
    'oldMotDePasse' => '',
    'newMotDePasse' => ''
];

if(isset($_SESSION['idUtilisateur'])){
    $idUtilisateur = $_SESSION['idUtilisateur'];
    $sqlUtilisateur = $conn->prepare("select * from utilisateur where idUtilisateur = :idUtilisateur");
    $sqlUtilisateur->bindParam(':idUtilisateur', $idUtilisateur);
    $sqlUtilisateur->execute();
    $utilisateur = $sqlUtilisateur->fetch(PDO::FETCH_ASSOC);
    print_r('hi');
}
if(isset($_POST['modifier'])){
    $idUtilisateur = $_SESSION['idUtilisateur'];
    //gerer les erreurs
     //input Nom
    if(empty($_POST['nom'])){
        $errors['nom'] = "le Nom est obligatoire !";
    } else {
        $newNom = $_POST['nom'];
    }
      //input Preom
    if(empty($_POST['prenom'])){
        $errors['prenom'] = "le preom est obligatoire !";
    } else {
        $newPrenom = $_POST['prenom'];
    }
    // old mot de passe
    if(empty(($_POST['oldMotDePasse']))){
        $errors['oldMotDePasse'] = "mot da passe est obligatoire";
    }else{
        // comparer le mot de passe exister avec le input 
        if(!password_verify($_POST['oldMotDePasse'], $utilisateur['motPasse'])){
        $errors['oldMotDePasse'] = "ancien mot da passe est incorrect !";
        }
    }
    // new mot de passe
    if(empty($_POST['newMotDePasse'])){
        $errors['newMotDePasse'] = "le mot de passe est obligatoire !";
    } else {
        $MotDePasse = $_POST['newMotDePasse'];
        $newMotDePasse = password_hash($MotDePasse, PASSWORD_DEFAULT);
    }
    if (!array_filter($errors)) {
        $sqlEdit = $conn->prepare("update utilisateur set nom = :newNom, prenom = :newPrenom ,motPasse =:newMotDePasse where idUtilisateur = :idUtilisateur");
        $sqlEdit->bindParam(':newNom', $newNom);
        $sqlEdit->bindParam(':newPrenom', $newPrenom);
        $sqlEdit->bindParam(':newMotDePasse', $newMotDePasse);
        $sqlEdit->bindParam(':idUtilisateur' , $idUtilisateur);
        $sqlEdit->execute();

                     header("location:profil.php");

        
    }
     
}
if(isset($_POST['annuler'])){
            header("location:profil.php");

}
?>

<?php 
include('include/header.php')
?>

<section>
    <form action="settings.php?id=<?php echo $idUtilisateur?>" method="POST">
        <h2>Paramètres</h2>

      <div class="mb-3">
    <label for="" class="form-label">Nom :</label>
    <input type="text" class="form-control" id="" name="nom" value="<?php echo $utilisateur['nom'] ?>">
    <div  class="form-text erreurs" style="color: red;"><?php echo $errors['nom']; ?></div>
  </div>

   <div class="mb-3">
    <label for="" class="form-label">Prenom :</label>
    <input type="text" class="form-control" id="" name="prenom" value="<?php echo $utilisateur['prenom'] ?>">
    <div  class="form-text erreurs" style="color: red;"><?php echo $errors['prenom']; ?></div>
  </div>
  <div class="mb-3">
    <label for="" class="form-label">Ancien mot de passe :</label>
    <input type="password" class="form-control" id="" name="oldMotDePasse">
    <div  class="form-text erreurs" style="color: red;"> <?php echo $errors['oldMotDePasse']; ?></div>

  </div>

  <div class="mb-3">
    <label for="" class="form-label">Nouveau mot de passe :</label>
    <input type="password" class="form-control" id="" name="newMotDePasse">
    <div  class="form-text erreurs" style="color: red;"> <?php echo $errors['newMotDePasse']; ?></div>

  </div>

  <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
  <button type="submit" class="btn btn-primary" name="annuler">Annuler</button>

  

</form>
</section>

<?php 
include('include/footer.php')
?>