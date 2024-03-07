<?php 
if(!isset($_SESSION)){
  session_start();
}
include 'include/conn.php';
if(isset($_SESSION['idUtilisateur'])){
    // select categorie 
    $sqlCategories = $conn->query("select distinct categorie from evenement ");
    $categories = $sqlCategories->fetchAll(PDO::FETCH_ASSOC);
    // select evenement 
    $sqlEvenementsTitre = $conn->query("select distinct titre from evenement ");
    $EvenementsTitre = $sqlEvenementsTitre->fetchAll(PDO::FETCH_ASSOC);
    
    // select salle
     $sqlSallesNum = $conn->query("select numSalle from salle ");
    $sallesNum = $sqlSallesNum->fetchAll(PDO::FETCH_ASSOC);
// ajouter evenement
    $errEvent = ['titre'=>"",
                 'description'=>"",
                 'tarifNormal' =>"",
                 'tarifReduit' =>"",
                'img'=>""];
    $errVersion = [
        'dateVersion' => ""
    ];
   
    $msgSuccess = "";

    //

    if (isset($_POST['addEvent'])) {

        if (empty($_POST['titre'])) {
            $errEvent['titre'] = "Veuillez saisir un titre pour l'événement. ";
        }else{
            $titre = $_POST['titre'];
            $sqlCheckTitre = $conn->prepare("select count(titre) from evenement where titre = :titre ");
            $sqlCheckTitre->bindParam(':titre', $titre );
            $sqlCheckTitre->execute();
            $CheckTitre = $sqlCheckTitre->fetch(PDO::FETCH_ASSOC);
            if($CheckTitre['count(titre)']>0){
                $errEvent['titre'] = "Cet événement existe déjà.";
            }else{
            $titreAjouter = $_POST['titre'];
            }
        }

        if (empty($_POST['description'])) {
            $errEvent['description'] = "Veuillez fournir une description pour l'événement. ";
        } else {
            $descriptionAjouter = $_POST['description'];

        }
        if (empty($_POST['tarifNormal'])) {
            $errEvent['tarifNormal'] = "Veuillez spécifier le tarif normal pour l'événement.";
        } else {
            $tarifNormalAjouter = $_POST['tarifNormal'];

        }
        if (empty($_POST['tarifReduit'])) {
            $errEvent['tarifReduit'] = "Veuillez spécifier le tarif reduit pour l'événement.";
        } else {
            $tarifReduitAjouter = $_POST['tarifReduit'];

        }
        if (empty($_POST['img'])) {
            $errEvent['img'] = "Veuillez fournir une image pour l'événement.";
        } else {
            $imgAjouter = $_POST['img'];

        }
        $categorieAjouter = $_POST['categorie'];
        if (!array_filter($errEvent)) {
            //ajouter un ligne aux tableau evenement
            $sqlAjouterEvenement = $conn->prepare("INSERT INTO evenement (titre,description,categorie, tarifNormal, tarifReduit, img) VALUES ( :titre, :description, :categorie, :tarifNormal, :tarifReduit, :img )");
            $sqlAjouterEvenement->bindParam(':titre', $titreAjouter);
            $sqlAjouterEvenement->bindParam(':description', $descriptionAjouter);
            $sqlAjouterEvenement->bindParam(':categorie', $categorieAjouter);
            $sqlAjouterEvenement->bindParam(':tarifNormal', $tarifNormalAjouter);
            $sqlAjouterEvenement->bindParam(':tarifReduit', $tarifReduitAjouter);
            $sqlAjouterEvenement->bindParam(':img', $imgAjouter);
            $sqlAjouterEvenement->execute();
            $msgSuccess= "evenement ajouter avec succés !";
           
        }
         }  


        //ajouter dans le tableau version
        if (isset($_POST['addVersion'])) {
        
        $versionEvenement = $_POST['evenement'];
        $currentDate = date('Y-m-d H:i:s');
        if(empty($_POST['date'])){
            $errVersion['dateVersion'] = "Veuillez indiquer la date de publication de cette version.";
        }elseif($_POST['date']< $currentDate){
            $errVersion['dateVersion'] = "La date de la version doit être ultérieure à la date d'aujourd'hui.";
        }else{
            $dateVersion = $_POST['date']; 
        }
        if(!array_filter($errVersion)){
            //id evenement 
            $titre = $_POST['evenement'];
            $sqlIdEvent = $conn->prepare("select idEvenement from evenement where titre = :titre");
            $sqlIdEvent->bindParam(':titre', $titre);
            $sqlIdEvent->execute();
            $idEvenement = $sqlIdEvent->fetch(PDO::FETCH_ASSOC);
            // num de salle
            $numSalle = $_POST['salle'];
            //num version 
            $sqlcountVersion = $conn->prepare("select count(*) from version where idEvenement = :idEvenement");
            $sqlcountVersion->bindParam(':idEvenement', $idEvenement['idEvenement']);
            $sqlcountVersion->execute();
            $countVersion = $sqlcountVersion->fetch(PDO::FETCH_ASSOC);
            $numVersion = $countVersion['count(*)'] + 1;
            
            //ajouter version
             $sqlAjouterVersion =$conn->prepare("INSERT INTO version(numVersion, dateEvenement, numSalle, idEvenement) VALUES(:numVersion, :dateEvenement, :numSalle, :idEvenement)") ;
            $sqlAjouterVersion->bindParam(':numVersion', $numVersion);
            $sqlAjouterVersion->bindParam(':dateEvenement', $dateVersion);
            $sqlAjouterVersion->bindParam(':numSalle', $numSalle);
            $sqlAjouterVersion->bindParam(':idEvenement', $idEvenement['idEvenement']);
            $sqlAjouterVersion->execute();
            $msgSuccess = "Version ajouter avec succés !";

        }
        }
        // ajouter salle 
       if(isset($_POST['addSalle'])){
       
       }
   
}else{
    header('location:login.php');
}
 ?>
<?php include 'include/header.php';
if(!empty($msgSuccess)){
    echo '<div class="alert alert-success" role="alert">' . $msgSuccess . '</div>';
}
?>
<section>
    <h1>Espace Admin</h1>
<div style=" display:inline-flex; align-items: center;justify-content: center;">
<form action="" method="POST" class="card  w-80" style="padding :20px ;margin: 10px;">
    <h3>Ajouter un evenement</h3>

    <div class="mb-3">
    <label for="">Titre de l'événement :</label>
    <input type="text" name="titre">
    <div class="form-text danger" style="color:red"><?php echo $errEvent['titre'] ?></div>
    </div>

    <div class="mb-3">
    <label for="">Description de l'événement :</label>
    <input type="text" name="description">
    <div class="form-text danger" style="color:red"><?php echo $errEvent['description'] ?></div>

    </div>

    <div class="mb-3">
        <label for="">Categories :</label>
    <select name="categorie" id="">
        <?php
        foreach($categories as $categorie){
            echo "<option>".$categorie['categorie']."</option>";
            
        } ?>
    </select>
    </div>

    <div class="mb-3">
    <label for="">Tarif normal :</label>
    <input type="number" name="tarifNormal">
    <div class="form-text danger" style="color:red"><?php echo $errEvent['tarifNormal'] ?></div>

    </div>

    <div class="mb-3">
    <label for="">Tarif Reduit :</label>
    <input type="number" name="tarifReduit">
    <div class="form-text danger" style="color:red"><?php echo $errEvent['tarifReduit'] ?></div>

    </div>

    <div class="mb-3">
     <label for="image">Ajouter une image :</label>
    <input type="text" name="img" id="image" placeholder="img/nomdephoto.png">
    <div class="form-text danger" style="color:red"><?php echo $errEvent['img'] ?></div>


    </div>

    <div class="mb-3">
        <input type="submit" name="addEvent" value="Ajouter" class="btn btn-primary">
    </div>
</form>

<form action="" method="POST" class="card  w-80" style="margin: 10px; padding :20px">

    <!-- ajouter version evenement -->
        <h3>Ajouter Version d'un Evenement</h3>
        <div class="mb-3">
        <label for="">Evenement :</label>
        <select name="evenement" id="">
            <?php
            foreach($EvenementsTitre as $EvenementTitre){
                echo "<option>".$EvenementTitre['titre']."</option>";
            }
            ?>
        </select>
         
       </div>

        <div class="mb-3">
        <label for="">Date d'evenement :</label>
        <input type="date" name="date">
        <div class="form-text danger" style="color:red"><?php echo $errVersion['dateVersion'] ?></div>
       </div>

       <div class="mb-3">
        <label for="">Salle :</label>
        <select name="salle" id="">
            <?php 
            foreach($sallesNum as $salleNum){
                echo "<option>" . $salleNum['numSalle'] . "</option>";
            }
            ?>
        </select>
       </div>

        <div class="mb-3">
        <input type="submit" name="addVersion" value="Ajouter" class="btn btn-primary">
    </div>

       </form>

      <!-- <form action="" class="card  w-80" style="margin: 10px; padding :20px">
        <h3>Ajouter une salle</h3>
        <div class="mb-3">
            <label for="">capacité de la salle :</label>
            <input type="text" name="descriptionSalle">
             </div>
        <div class="mb-3">
            <label for="">Num de la salle :</label>
            <input type="number">
            </div>

        <div class="mb-3">
            <label for="">description de la salle :</label><br>
            <textarea name="descriptionSalle"></textarea>
            </div>

             <div class="mb-3">
        <input type="submit" name="addSalle" value="Ajouter" class="btn btn-primary">
    </div>
</form> -->
</div>

<table></table>
</section>
<?php include'include/footer.php'?>
