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

            $query = $dbh->prepare('INSERT INTO isolation(status, logement_type, genre, nom, prenom, adresse, ville, code_postal, telephone, email, acceptations, offre) VALUES(:status, :logement_type,  :genre, :nom, :prenom, :adresse, :ville, :code_postal, :telephone, :email, :acceptations, :offre)');
            $query->execute($query_params);
            header("location: /home-page/index.html");
            exit;

        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            return false;
        }
    }
}

/*CREATE TABLE isolation (
  id INT NOT NULL AUTO_INCREMENT,
  status VARCHAR(255),
  logement_type VARCHAR(255),
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
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <title>EcoSolutions - Isolation thermique extérieure</title>
    <meta name="description" content="Calculez vos aides de la prime Ma prime renov' pour réaliser une isolation thermique extérieur. Obtenez une estimation de vos aides financières avec notre formulaire en ligne.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/isolation.css">
</head>

<body>
<header>
    <div id="title">
        <h1>NOUVELLES AIDES 2023, FAITES UNE ISOLATION THERMIQUE EXTÉRIEUR !</h1>
    </div>

    <div id="txt-form-header">
        <p id="container-text-header">
            RÉPONDEZ A NOTRE <span>QUESTIONNAIRE RAPIDE</span> POUR NOUS PERMETTRE DE VOUS ACCOMPAGNER DANS  LA RÉALISATION DE VOTRE<span> ISOLATION THERMIQUE EXTÉRIEUR !</span><br><br>

            DES AIDES <span>(JUSQU’A 15 000€)</span><br><br>
            DES <span>ÉCONOMIES</span> <br><br>
            UNE CONSOMATION <span>RÉDUITE</span>
        </p>
        <div id="form">
            <p>Trouvez le meilleur montant d’aide pour vos travaux d’isolations thermiques extérieures</p>
            <form action="isolation.php" method="POST">
                <div class="f2pl">
                    <select id="status" name="status" required>
                        <option value="">→ Vous êtes... <sup>*</sup></option>
                        <option value="owner">Propiétaire</option>
                        <option value="tenant">Locataire</option>
                    </select>
                    <label for="logement_type"></label>
                    <select id="logement_type" name="logement_type" required>
                        <option value="">→ Type de logement... <sup>*</sup></option>
                        <option value="house">Une maison</option>
                        <option value="appartement">Un appartement</option>
                        <option value="business premises">Locaux professionnels</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="fl exeption">
                    <label for="genre">Genre : </label required>
                    <input type="radio" id="homme" name="genre" value="homme">
                    <label for="homme">Homme</label>

                    <input type="radio" id="femme" name="genre" value="femme">
                    <label for="genre">Femme</label>

                    <input type="radio" id="non-defini" name="genre" value="non-defini">
                    <label for="genre">Non défini</label>
                </div>

                <div class="f2pl">
                    <input type="text" id="nom" name="nom" placeholder="→ Nom *" autocomplete="nom" required>

                    <input type="text" id="prenom" name="prenom" placeholder="→ Prénom *" autocomplete="prenom" required>
                </div>

                <div class="fl">
                    <input type="text" id="adresse" name="adresse" placeholder="→ Adresse " autocomplete="adresse">
                </div>

                <div class="f2pl">
                    <input type="text" id="ville" name="ville" placeholder="→ Ville " autocomplete="ville">

                    <input type="text" id="code_postal" name="code_postal" placeholder="→ Code Postal *" autocomplete="codePostal" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_code_postal'])) { ?>
                        <p id="error1"><?php echo $errors['lenght_postal_code']; ?></p>
                    <?php } ?>
                </div>

                <div class="fl">
                    <label for="email"></label>
                    <input type="text" id="email" name="email" placeholder="→ Adresse e-mail *" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['preg_email'])) { ?>
                        <p id="error1"><?php echo $errors['preg_email']; ?></p>
                    <?php } ?>
                </div>

                <div class="fl">
                    <label for="phone"></label>
                    <input type="text" id="phone" name="phone" placeholder="→ Numéro de téléphone *" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_phone'])) { ?>
                        <p id="error2"><?php echo $errors['lenght_phone']; ?></p>
                    <?php } ?>
                </div>

                <div id="fb">
                    <div class="exeption" id="acceptations">
                        <input type="checkbox" id="cgu" name="cgu" required>
                        <label for="cgu">J'accepte les CGU et que leurs partenaires me communiquent leurs devis *</label>

                        <div class="fb">
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
    <h2>Recevez la meilleure offre pour concrétiser votre projet</h2>
    <div class="under-containt">
        <img src="img/Architecture.gif" alt="un gif d'une maison sur un plan pour illustrer le texte" id="first-image">
        <div class="txt-prime">
            <h3>EN 2023, MA PRIME RÉNOV S’ÉTEND !</h3>
            <p>
                Avec la réalisation de votre isolation thermique extérieur, vous pouvez bénéficier de <strong>plusieurs aides
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
                et respectueux de l'environnement.
            </p>
        </div>
        <img src="img/ConstructionPlanning.gif" alt="un gif d'un plan de construction pour illustrer le texte" id="green-house">
    </div>
    <div class="contant-button">
        <a href="#form"><button>VÉRIFIER MON ÉLIGIBILITÉ</button></a>
    </div>
</div>

<div class="bandeau">
    <h2>TOUT SAVOIR SUR L’ISOLATION THERMIQUE EXTÉRIEUR</h2>
</div>

<div class="contenu" id="contenu2">
    <div class="under-containt">
        <img src="img/architecture-engineer.png" alt="illustration d'ingénieur qui organise un chantier" id="illustration">
        <div class="txt-prime">
            <h3 id="pac">ISOLATION THERMIQUE EXTÉRIEUR</h3>
            <p id="bold">
                L'isolation thermique par l'extérieur (ITE) consiste à placer une couche d'isolant thermique sur
                les murs extérieurs d'un bâtiment pour réduire les pertes de chaleur et réaliser des
                économies d'énergie.
            </p>
            <br><br>
            <ul>
                <li>→ <span class="green-list">Économique,</span> permet de réduire les pertes de chaleur et de réaliser des économies d'énergie significatives.</li>
                <li>→ <span class="green-list">Confortable,</span> améliore le confort thermique et acoustique de l'habitat.</li>
                <li>→ <span class="green-list">Durable,</span> contribue à la durabilité et à la longévité de la structure du bâtiment.</li>
                <li>→ <span class="green-list">Écologique,</span> réduit les émissions de gaz à effet de serre en réduisant la consommation d'énergie et en utilisant des matériaux respectueux de l'environnement.</li>
                <li>→ <span class="green-list">Valorisation immobilière,</span> l'ITE peut augmenter la valeur du bien immobilier en le rendant plus économe en énergie, plus confortable et plus esthétique, ce qui peut être un avantage lors de la revente.</li>
            </ul>
            <a href="#form"><button>COMMENCER MON ISOLATION</button></a>
        </div>
    </div>
</div>

<div class="bandeau">
    <h2>DISPOSITIF DE L’ÉTAT</h2>
    <div id="logo">
        <img src="img/Groupprimerenov.png" alt="logo de Ma prime rénov'">
        <img src="img/CEE-Certificat-Economie-Energie1cee.png" alt="logo des certificats d'économies d'énergie">
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
