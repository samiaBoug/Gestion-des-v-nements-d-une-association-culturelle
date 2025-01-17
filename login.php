<?php
include('include/conn.php');
if(!isset($_SESSION)){
  session_start();
}

$errors = [
    'email' => '',
    'motDePasse' => ''
];
if(isset($_SESSION['idUtilisateur'])){
    header('location:profil.php');
}else{

if(isset($_POST['login'])){
    // verifier email 
    if(empty($_POST['email'])){
        $errors['email'] = "email est obligatoire";
    }else {
        $checkEmail = $_POST['email'];
         $sqlEmails = $conn->query("select count(email) from utilisateur where email = '$checkEmail' ");
         $EmailCount = $sqlEmails->fetch(PDO::FETCH_ASSOC);
        
        if($EmailCount['count(email)']=== 0){
            $errors['email'] = "email n'exist pas ";
        } 
        else{
            $email = $_POST['email'];
        }
       // verifier mot de passe
       if (empty($_POST['motDePasse'])) {
        $errors['motDePasse'] = "Mot de passe est obligatoire";
        } else {
        $sqlMotDePasse = $conn->query("select motPasse from utilisateur where email = '$email'");
        $MotDePassehaché = $sqlMotDePasse->fetch(PDO::FETCH_DEFAULT);
        $MotDePasse= $MotDePassehaché['motPasse'];
        $MotDePasseEntre =trim($_POST['motDePasse'] );
        if (!password_verify($MotDePasseEntre, $MotDePasse)) {
            $errors['motDePasse'] = "mot de passe est incorrect";
           
        }else{
            // recuperer id 
            $sqlId = $conn->query("select idUtilisateur from utilisateur where email = '$email'");
            $id = $sqlId->fetch(PDO::FETCH_ASSOC);
            $idUtilisateur = $id['idUtilisateur'];
            if(stripos($email, 'admin') !== false){
               $_SESSION['idUtilisateur'] = $idUtilisateur;
                header("location:admin.php?id=$idUtilisateur"); 
            }else{
            $_SESSION['idUtilisateur'] = $idUtilisateur;
            header("location:profil.php?id=$idUtilisateur");
             }   
        }
        
    }
    }
}
}
?>
<?php 
include('include/header.php')
?>
<section class="container container-fluid">
    <form action="login.php" method="POST">
     <h2>Connexion</h2>
      <div class="mb-3">
        <label for="" class="">Email</label>
        <input type="email" class="form-control" id="" name="email">
        <div  class="form-text erreurs" style="color: red;"> <?php echo $errors['email']; ?></div>
     </div>

  <div class="mb-3">
    <label for="" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="" name="motDePasse">
    <div  class="form-text erreurs" style="color: red;"> <?php echo $errors['motDePasse']; ?></div>

  </div>

  <button type="submit" class="btn btn-primary" name="login">Connexion</button>

  <p>Vous n'avez pas un compte ? <a href="signup.php">Inscription</a></p>
</form>


</section>
 
 


<?php 
include('include/footer.php')
?>