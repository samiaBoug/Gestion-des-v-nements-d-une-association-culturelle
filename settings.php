<?php 
include('include/conn.php');
$errors=[
    'nom' => '',
    'prenom' => '',
     'email' => '',
    'motDePasse' => ''
];

if(isset($_GET['id'])){
    $idUtilisateur = $_GET['id'];

    $sqlUtilisateur = $conn->query("select * from utilisateur where idUtilisateur = '$idUtilisateur'");
    $utilisateur = $sqlUtilisateur->fetch(PDO::FETCH_ASSOC);
}
if(isset($_POST['modifier'])){
    $idUtilisateur = $_GET['id'];
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
       //input email
    if(empty($_POST['email'])){
        $errors['email'] = "email est obligatoire !";
    }else {
        $checkEmail = $_POST['email'];
         $sqlEmails = $conn->query("select count(email) from utilisateur where email = '$checkEmail' ");
         $Emails = $sqlEmails->fetchAll(PDO::FETCH_ASSOC);
        if($Emails[0]['count(email)']>0){
            $errors['email'] = "email déja exist ";
        }else{
            $newEmail = $_POST['email'];
        }
        
    }
    if(empty($_POST['motDePasse'])){
        $errors['motDePasse'] = "le mot de passe est obligatoire !";
    } else {
        $MotDePasse = $_POST['motDePasse'];
        $newMotDePasse = password_hash($MotDePasse, PASSWORD_DEFAULT);
    }
    if (!array_filter($errors)) {
        $sqlEdit = $conn->query("update utilisateur set nom = '$newNom', prenom = '$newPrenom',email='$newEmail',motPasse ='$newMotDePasse' where idUtilisateur ='$idUtilisateur'");
                     header("location:profil.php?id=$idUtilisateur");

        
    }
     
}
if(isset($_POST['annuler'])){
            header("location:profil.php?id=$idUtilisateur");

}
?>

<?php 
include('include/header.php')
?>

<section>
    <form action="settings.php?id=<?php echo $idUtilisateur?>" method="POST">
        <h2>Paramètres</h2>

      <div class="mb-3">
    <label for="" class="form-label">Nom</label>
    <input type="text" class="form-control" id="" name="nom" value="<?php echo $utilisateur['nom'] ?>">
    <div  class="form-text msgError"><?php echo $errors['nom']; ?></div>
  </div>

   <div class="mb-3">
    <label for="" class="form-label">Prenom</label>
    <input type="text" class="form-control" id="" name="prenom" value="<?php echo $utilisateur['prenom'] ?>">
    <div  class="form-text msgError"><?php echo $errors['prenom']; ?></div>
  </div>
         
  <div class="mb-3">
    <label for="" class="">Email</label>
    <input type="email" class="form-control" id="" name="email" value="<?php echo $utilisateur['email'] ?>">
    <div  class="form-text"> <?php echo $errors['email']; ?></div>
  </div>

  <div class="mb-3">
    <label for="" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="" name="motDePasse">
    <div  class="form-text"> <?php echo $errors['motDePasse']; ?></div>

  </div>

  <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
  <button type="submit" class="btn btn-primary" name="annuler">Annuler</button>

  

</form>
</section>

<?php 
include('include/footer.php')
?>