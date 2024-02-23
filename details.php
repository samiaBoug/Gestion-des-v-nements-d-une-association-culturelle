<?php
include('include/conn.php');

?>
<?php include('include/header.php') ?>
<section class="container">

<div class="card" style="width: 18rem;">
  <img src="" class="card-img-top" alt="">
  <div class="card-body">
    <h1 class="card-title">Détails de l'évenement </h1>
    <h2 class="card-title">titre de l'évenement</h2>
    <h3 class="card-title"> categorie </h3>
    <p class="card-text">la date de l'evenement</p>
    <div class="mb-3">
     <form action="">
      <label for="" class="form-label">selectionner les billets</label>
      <input type="number" value=1 min=1 class="form-control">
      <input class="form-control" type="submit" name="buyTicket" value="Acheter un billet" class="btn btn-primary">
     </form>
    </div>
  </div>
</div>


<p>description</p>
<h4> </h4>
<h5> tarif normale </h5>
<h5> tarif reduit </h5>



</section>

<?php include('include/footer.php') ?>
