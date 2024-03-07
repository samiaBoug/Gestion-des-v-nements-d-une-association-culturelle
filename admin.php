<?php 
if(!isset($_SESSION)){
  session_start();
}
include 'include/conn.php';
if(isset($_SESSION['idUtilisateur'])){
    // select categorie 
    $sqlCategories = $conn->query("select distinct categorie from evenement ");
    $categories = $sqlCategories->fetchAll(PDO::FETCH_ASSOC);
// ajouter evenement
 $errEvent = ['titre'=>"",
                 'description'=>"",
                 'tarifNormal' =>"",
                 'tarifReduit' =>"",
                'img'=>""];
if(isset($_POST['addEvent'])){
       
    if(empty($_POST['titre'])){
            $errEvent['titre'] = "Veuillez saisir un titre pour l'événement. ";
    }else{
            $titreAjouter = $_POST['titre'];
    }

    if(empty($_POST['description'])){
            $errEvent['description'] = "Veuillez fournir une description pour l'événement. ";
    }else{
            $descriptinAjouter = $_POST['description'];
    }
    if(empty($_POST['tarifNormal'])){
            $errEvent['tarifNormal'] = "Veuillez spécifier le tarif normal pour l'événement.";
    }else{
            $tarifNormalAjouter = $_POST['tarifNormal'];
    }
    if(empty($_POST['tarifReduit'])){
            $errEvent['tarifReduit'] = "Veuillez spécifier le tarif reduit pour l'événement.";
    }else{
            $tarifReduitAjouter = $_POST['tarifReduit'];
    }
    if(empty($_POST['img'])){
            $errEvent['img'] = "Veuillez fournir une image pour l'événement.";
    }else{
            $imgAjouter = $_POST['img'];
            echo $imgAjouter;
    }
}

}else{
    header('location:login.php');
}
 ?>
<?php include'include/header.php' ?>
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
     <label for="image">Télécharger une image :</label>
    <input type="file" name="image" id="image" accept="image/*" name="img">
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
            <option value="">Evenement</option>
        </select>
       </div>

        <div class="mb-3">
        <label for="">Date d'evenement :</label>
        <input type="date" name="dateEvenement">
       </div>

       <div class="mb-3">
        <label for="">Salle :</label>
        <select name="salle" id="">
            <option value="">salle</option>
        </select>
       </div>

        <div class="mb-3">
        <input type="submit" name="addVersion" value="Ajouter" class="btn btn-primary">
    </div>

       </form>

      <form action="" class="card  w-80" style="margin: 10px; padding :20px">
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
</form>
</div>

<table></table>
</section>
<?php include'include/footer.php'?>
