<?php
// Include necessary dependencies and database connection
require_once __DIR__ . '/pdf/autoload.php';

include('include/conn.php');

// Check if the "id" parameter is present in the URL
if (isset($_GET['id'])) {
    $idFacture = $_GET['id'];

    // Fetch data from the database
    $sqlFacture = $conn->prepare("SELECT * FROM facture WHERE idFacture = :idFacture");
    $sqlFacture->bindParam(':idFacture', $idFacture);
    $sqlFacture->execute();
    $facture = $sqlFacture->fetch(PDO::FETCH_ASSOC);

    // Fetch additional data (adjust the query based on your database structure)
    $sqlFactureEvent = $conn->query("SELECT version.idVersion FROM facture INNER JOIN version ON facture.idVersion=version.idVersion WHERE facture.idFacture='$idFacture'");
    $factureEvent = $sqlFactureEvent->fetch(PDO::FETCH_ASSOC);
    $idVersion = $factureEvent['idVersion'];

    $sqlEvent = $conn->query("SELECT * FROM evenement INNER JOIN version ON evenement.idEvenement=version.idEvenement WHERE version.idVersion='$idVersion'");
    $event = $sqlEvent->fetch(PDO::FETCH_ASSOC);

    $sqlBilletsNormal = $conn->query("SELECT COUNT(codeBillet) FROM billet WHERE idFacture='$idFacture' AND typeBillet='normal'");
    $nmbreBilletNormale = $sqlBilletsNormal->fetch(PDO::FETCH_ASSOC);

    $sqlBilletsReduit = $conn->query("SELECT COUNT(codeBillet) FROM billet WHERE idFacture='$idFacture' AND typeBillet='reduit'");
    $nmbreBilletReduit = $sqlBilletsReduit->fetch(PDO::FETCH_ASSOC);

    $sqlBillets = $conn->query("SELECT * FROM billet INNER JOIN facture ON billet.idFacture=facture.idFacture WHERE facture.idFacture='$idFacture'");
    $billets = $sqlBillets->fetchAll(PDO::FETCH_ASSOC);

    // Create an instance of mPDF
    $mpdf = new \Mpdf\Mpdf();
    $stylesheet = file_get_contents(__DIR__ . '/css/style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);


    // Start buffering the output
    ob_start();

    // Include only the section content (excluding header and footer)
?>
<section>
    <style>
    .card-facture{
    
    width: 60%;
    margin: 2% 20%;

}
.card-facture * {
    margin: 10px 20px;
}
.header-facture{
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px ;
}
</style>
    <h3>Votre facture : </h3>
    <div class="card card-facture">
        <div class="header-facture">
            <!-- Nom association adresse -->
            <div>
                <h2>ASSOCIATION FARHA</h2>
                <h6>center culturel farha Tanger</h6>
            </div>
            <!-- nom client adresse email -->
            <div>
                <p> client : <?php echo isset($facture['nom']) ? $facture['nom'] : ''; ?> <?php echo isset($facture['prenom']) ? $facture['prenom'] : ''; ?></p>
                <p>Adresse email : <?php echo isset($facture['email']) ? $facture['email'] : ''; ?> </p>
            </div>
        </div>
        <!--titre evenement et date  -->
        <div>
            <h5> <?php echo isset($event['titre']) ? $event['titre'] : ''; ?></h5>
            <h6> Le <?php echo isset($event['dateEvenement']) ? $event['dateEvenement'] : ''; ?></h6>
        </div>
        <!-- facture ID -->
        <div>
            <h2> FACTURE#<?php echo isset($facture['idFacture']) ? $facture['idFacture'] : ''; ?> </h2>
        </div>
        <!-- table -->
        <table class="table">
            <tr>
                <th>Tarif</th>
                <th>Prix</th>
                <th>Qte</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Normal</td>
                <td><?php echo isset($event['tarifnormal']) ? $event['tarifnormal'] : ''; ?></td>
                <td><?php echo isset($nmbreBilletNormale['count(codeBillet)']) ? $nmbreBilletNormale['count(codeBillet)'] : ''; ?></td>
                <td><?php echo isset($event['tarifnormal']) && isset($nmbreBilletNormale['count(codeBillet)']) ? $event['tarifnormal'] * $nmbreBilletNormale['count(codeBillet)'] : ''; ?> DH</td>
            </tr>
            <tr>
                <td>Reduit</td>
                <td><?php echo isset($event['tarifReduit']) ? $event['tarifReduit'] : ''; ?></td>
                <td><?php echo isset($nmbreBilletReduit['count(codeBillet)']) ? $nmbreBilletReduit['count(codeBillet)'] : ''; ?></td>
                <td><?php echo isset($event['tarifReduit']) && isset($nmbreBilletReduit['count(codeBillet)']) ? $event['tarifReduit'] * $nmbreBilletReduit['count(codeBillet)'] : ''; ?> DH</td>
            </tr>
        </table>
        <div>Totale à payer : <?php
            $totalAmount = (isset($event['tarifnormal']) ? $event['tarifnormal'] * (isset($nmbreBilletNormale['count(codeBillet)']) ? $nmbreBilletNormale['count(codeBillet)'] : 0) : 0)
                + (isset($event['tarifReduit']) ? $event['tarifReduit'] * (isset($nmbreBilletReduit['count(codeBillet)']) ? $nmbreBilletReduit['count(codeBillet)'] : 0) : 0);
            echo $totalAmount; ?> DH</div>
        <span>MERCI !</span>
    </div>

    <h3>Vos billets</h3>

    <?php
    foreach ($billets as $billet) {
        $tarif = "";
        if ($billet['typeBillet'] === "normal") {
            $tarif = isset($event['tarifnormal']) ? $event['tarifnormal'] : '';
        } elseif ($billet['typeBillet'] === "Reduit") {
            $tarif = isset($event['tarifReduit']) ? $event['tarifReduit'] : '';
        }
    ?>
        <div class="card card-billet row">
            <!-- partie 1 billet -->
            <div class="partie1-Billet col-3">
                Numéro de ticket: #<?php echo isset($billet['codeBillet']) ? $billet['codeBillet'] : ''; ?>
            </div>
            <!-- partie2 -->
            <div class="partie2-Billet col-3">
                <h2><?php echo isset($event['titre']) ? $event['titre'] : ''; ?></h2>
                <h4><?php echo isset($event['dateEvenement']) ? $event['dateEvenement'] : ''; ?></h4>
            </div>
            <!-- partie 3 -->
            <div class="partie3-Billet col-3">
                <h2>ASSOCIATION FARHA</h2>
                <h6>tarif : <?php echo 'DH ' . $tarif; ?> </h6>
                <h6>Addresse: Centre culturel Farha, Tanger</h6>
            </div>
            <!-- partie 4 -->
            <div class="partie3-Billet col-3">
                <img src="img/codbar.jpg" width="60px" height="20px">
                <div class="place-salle">
                    <h5>Place :<?php echo isset($billet['numPlace']) ? $billet['numPlace'] : ''; ?></h5>
                    <h5>Salle :<?php echo isset($event['numSalle']) ? $event['numSalle'] : ''; ?></h5>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</section>
<?php
    // Get the content of the buffer
    $content = ob_get_clean();

    // Write the HTML content to mPDF
    $mpdf->WriteHTML($content);

    // Output the PDF
    $mpdf->Output();
    exit;
}
?>



