<?php
try {
    $dbh = new PDO('mysql:host=localhost:3306;dbname=root2;charset=utf8', 'root2', 'root');
} catch (Exception $e) {
    die('Erreur de connexion' . $e->getMessage());
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Codition Code postal
    if (!preg_match("~^\d{5}$~", $_POST['code_postal'])) {
        $errors['lenght_postal_code'] = "Le code postal n'est pas conforme";
    }

    //Codition email
    if (!preg_match("~^.+@.+\..+$~", $_POST['email'])) {
        $errors['preg_email'] = "l'e-mail est non conforme";
    }

    //Codition numéro de téléphone
    if (!preg_match("~^\d{10}$~", $_POST['telephone'])) {
        $errors['lenght_phone'] = "Le numéro renseigner n'existe pas";
    }

    //condition cgu
    if ($_POST['cgu'] == false) {
        $errors['cgu'] = "Veuillez sélectionner un champ";
    }


    if (empty($errors)) {
        try {

            $query_params = array(
                ':status' => $_POST['status'],
                ':chauffage_actuel' => $_POST['chauffage_actuel'],
                ':logement_type' => $_POST['logement_type'],
                ':genre' => $_POST['genre'],
                ':nom' => $_POST['nom'],
                ':prenom' => $_POST['prenom'],
                ':adresse' => $_POST['adresse'],
                ':ville' => $_POST['ville'],
                ':code_postal' => $_POST['code_postal'],
                ':telephone' => $_POST['telephone'],
                ':email' => $_POST['email'],
                ':acceptations' => $_POST['acceptations'],
                ':offre' => $_POST['offre']
            );

            $query = $dbh->prepare('INSERT INTO poele_granule(status, chauffage_actuel, logement_type, genre, nom, prenom, adresse, ville, code_postal, telephone, email, acceptations, offre) VALUES(:status, :chauffage_actuel, :logement_type,  :genre, :nom, :prenom, :adresse, :ville, :code_postal, :telephone, :email, :acceptations, :offre)');
            $query->execute($query_params);
            header("location: /home-page/index.html");
            exit;

        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            return false;
        }
    }
}

/*CREATE TABLE poele_granule (
  id INT NOT NULL AUTO_INCREMENT,
  status VARCHAR(255),
  logement_type VARCHAR(255),
  chauffage_actuel VARCHAR(255),
  genre VARCHAR(255),
  nom VARCHAR(255),
  prenom VARCHAR(255),
  adresse VARCHAR(255),
  code_postal VARCHAR(5),
  ville VARCHAR(255),
  telephone VARCHAR(10),
  email VARCHAR(255),
  acceptations BOOLEAN,
  offre BOOLEAN,
  PRIMARY KEY (id)
);*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
@@ -25,15 +108,15 @@ <h1>NOUVELLES AIDES 2023, INSTALLEZ VOTRE POÊLE À GRANULÉS !</h1>
        </p>
        <div id="form">
            <p>Recevez la meilleure offre pour concrétiser votre projet</p>
            <form action="/home-page/redirectory.html" method="POST">
            <form action="poele_granule.php" method="POST">
                <div class="f2pl">
                    <select id="status" name="status">
                    <select id="status" name="status" required>
                        <option value="">→ Vous êtes... <sup>*</sup></option>
                        <option value="owner">Propiétaire</option>
                        <option value="tenant">Locataire</option>
                    </select>

                    <select id="heating" name="heating">
                    <select id="heating" name="chauffage_actuel" required>
                        <option value="">→ Votre chauffage actuel...<sup>*</sup></option>
                        <option value="gas">Gaz</option>
                        <option value="fuel_oil">Fioul</option>
@@ -45,8 +128,8 @@ <h1>NOUVELLES AIDES 2023, INSTALLEZ VOTRE POÊLE À GRANULÉS !</h1>
                </div>

                <div class="fl">
                    <label for="accommodation"></label>
                    <select id="accommodation" name="accommodation">
                    <label for="logement_type"></label>
                    <select id="logement_type" name="logement_type" required>
                        <option value="">→ Type de logement... <sup>*</sup></option>
                        <option value="house">Une maison</option>
                        <option value="appartement">Un appartement</option>
@@ -56,20 +139,21 @@ <h1>NOUVELLES AIDES 2023, INSTALLEZ VOTRE POÊLE À GRANULÉS !</h1>
                </div>

                <div class="fl exeption">
                    <input type="checkbox" id="male" name="male">
                    <label for="male">Mr</label>
                    <label for="genre">Genre : </label required required>
                    <input type="radio" id="homme" name="genre" value="homme">
                    <label for="homme">Homme</label>

                    <input type="checkbox" id="female" name="female">
                    <label for="female">Mme</label>
                    <input type="radio" id="femme" name="genre" value="femme">
                    <label for="genre">Femme</label>

                    <input type="checkbox" id="other_sex" name="other_sex">
                    <label for="other_sex">Non définis</label>
                    <input type="radio" id="non-defini" name="genre" value="non-defini">
                    <label for="genre">Non défini</label>
                </div>

                <div class="f2pl">
                    <input type="text" id="nom" name="nom" placeholder="→ Nom *" autocomplete="nom">
                    <input type="text" id="nom" name="nom" placeholder="→ Nom *" autocomplete="nom" required>

                    <input type="text" id="prenom" name="prenom" placeholder="→ Prénom *" autocomplete="prénom">
                    <input type="text" id="prenom" name="prenom" placeholder="→ Prénom *" autocomplete="prénom" required>
                </div>

                <div class="fl">
@@ -79,23 +163,48 @@ <h1>NOUVELLES AIDES 2023, INSTALLEZ VOTRE POÊLE À GRANULÉS !</h1>
                <div class="f2pl">
                    <input type="text" id="ville" name="ville" placeholder="→ Ville" autocomplete="ville">

                    <input type="text" id="code_postal" name="code_postal" placeholder="→ Code Postal *" autocomplete="codePostal">
                    <input type="text" id="code_postal" name="code_postal" placeholder="→ Code Postal *" autocomplete="codePostal" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_code_postal'])) { ?>
                        <p id="error1"><?php echo $errors['lenght_postal_code']; ?></p>
                    <?php } ?>
                </div>

                <div class="fl">
                    <label for="email"></label>
                    <input type="text" id="email" name="email" placeholder="→ Adresse e-mail *" autocomplete="email">
                    <input type="text" id="email" name="email" placeholder="→ Adresse e-mail *" autocomplete="email"required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['preg_email'])) { ?>
                        <p id="error1"><?php echo $errors['preg_email']; ?></p>
                    <?php } ?>
                </div>

                <div class="fl">
                    <label for="telephone"></label>
                    <input type="text" id="telephone" name="telephone" placeholder="→ Numéro de téléphone *" autocomplete="phone">
                    <input type="text" id="telephone" name="telephone" placeholder="→ Numéro de téléphone *" autocomplete="phone"required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_phone'])) { ?>
                        <p id="error2"><?php echo $errors['lenght_phone']; ?></p>
                    <?php } ?>
                </div>

                <div id="fb">
                    <div class="exeption" id="acceptations">
                        <input type="checkbox" id="cgu" name="cgu">
                        <input type="checkbox" id="cgu" name="cgu" required>
                        <label for="cgu">J'accepte les CGU et que leurs partenaires me communiquent leurs devis *</label>

                        <div class="fl">
                            <?php if (isset($errors['cgu'])) { ?>
                                <p id="error3"><?php echo $errors['cgu']; ?></p>
                            <?php } ?>
                        </div>

                        <br><br>

                        <input type="checkbox" id="offre" name="offre">
                        <label for="offre">J'accepte de recevoir des offres personnalisées email, téléphone et sms de EcoConfortSolution.fr ainsi que de ses partenaires</label>
                    </div>
                    <input type="submit" value="VALIDER" id="hbutton">
                </div>
            </form>
        </div>
    </div>
</header>
<div class="contenu">
    <h2>Faites des économies sur vos factures d’énergie !</h2>
    <div class="under-containt">
        <img src="img/stove.png" alt="poêle à granulés" id="first-img">
        <div class="txt-prime">
            <h3>EN 2023, MA PRIME RÉNOV S’ÉTEND !</h3>
            <p>
                Avec l'installation de panneaux solaires, vous pouvez bénéficier de <strong>plusieurs aides
                financières</strong> de l'État pour réduire vos coûts d'installation. Depuis 2023, Ma Prime
                Renov' est étendue à <strong>tous les propriétaires</strong>, offrant un soutien financier accru pour réduire
                votre empreinte carbone et améliorer votre confort. Vous pouvez également bénéficier de la
                prime CEE. <strong>Remplissez notre questionnaire</strong> pour savoir si vous êtes éligible à cette prime et
                transformez votre maison en un lieu de vie plus confortable et respectueux de l'environnement
                dès maintenant.
            </p>
        </div>
    </div>
    <div class="under-containt">
        <div class="txt-prime">
            <h3>BÉNÉFICIEZ DE LA PRIME C.E.E</h3>
            <p>
                La prime CEE est une <strong>aide financière</strong> offerte par l'État pour encourager les particuliers à
                adopter des comportements éco-responsables. En réalisant des travaux de rénovation énergétique
                ou en installant des équipements performants, vous pouvez <strong>bénéficier de cette prime</strong> et faire
                ainsi des <strong>économies</strong> sur vos factures d'énergie. <strong>Remplissez notre questionnaire</strong> pour découvrir
                si vous êtes éligible à la prime CEE et transformez votre maison en un lieu de vie plus confortable
                et respectueux de l'environnement.            </p>
        </div>
        <img src="img/pellet-stove.jpg" alt="poêle à granulés" id="green-house">
    </div>
    <div class="contant-button">
        <a href="#form"><button>VÉRIFIER MON ÉLIGIBILITÉ</button></a>
    </div>
</div>
<div class="bandeau">
    <h2>TOUT SAVOIR SUR LES POÊLES À GRANULÉS</h2>
</div>
<div class="contenu" id="contenu2">
    <div class="under-containt">
        <img src="img/people-use-bio-coal.png" alt="illustration de panneaux solaires" id="illustration">
        <div class="txt-prime">
            <h3 id="pac">POÊLE À GRANULÉS </h3>
            <p id="bold">
                Un poêle à granulés est un appareil de chauffage fonctionnant avec des granulés de bois comprimés.
                Il utilise une combustion propre et efficace pour produire de la chaleur, offrant ainsi une
                alternative plus écologique et économique aux méthodes de chauffage traditionnelles.
            </p>
            <br><br>
            <ul>
                <li>→ <span class="green-list">Utilise des granulés de bois</span>, une source de combustible renouvelable et écologique.</li>
                <li>→ <span class="green-list">Économique</span>, il permet de réaliser des économies sur la facture énergétique grâce à une consommation électrique réduite.</li>
                <li>→ <span class="green-list">Stable</span>, il offre une chaleur constante et régulière, programmable selon les besoins.</li>
                <li>→ <span class="green-list">Peu d’entretiens</span>, facile à utiliser et à entretenir, avec un nettoyage automatique du brûleur et une faible accumulation de cendres.</li>
                <li>→ <span class="green-list">Écologique</span>, avec son émission faible en carbonne ce mode de chauffage est une option plus respectueuse de l'environnement que les poêles à bois traditionnels.
                </li>
                <li>→ <span class="green-list">Flexible</span>, le poêle à granulés est utilisable comme système de chauffage principal ou d'appoint, offrant ainsi une flexibilité d'utilisation.</li>
            </ul>
            <a href="#form"><button>OBTENIR MON POÊLE A GRANULÉS</button></a>
        </div>
    </div>
</div>
<div class="bandeau">
    <h2>DISPOSITIF DE L’ÉTAT</h2>
    <div id="logo">
        <img src="img/Groupprimerenov.png" alt="logo de Ma prime rénov'">
        <img src="img/CEE-Certificat-Economie-Energie%201cee.png" alt="logo des certificats d'économies d'énergie">
        <img src="img/phast.png" alt="logo de France renov'">
        <img src="img/phast2.png" alt="logo de RGE eco artisan et logo de RGE QUALIBAT">
    </div>
    <div class="contant-button">
        <a href="#form"><button id="no-shadow">CALCULER MES AIDES</button></a>
    </div>
</div>
<footer>
    <p>
        ©2023 Tous droits réservés.| EcoConfortSolutions.com
    </p>
</footer>
</body>
</html>
