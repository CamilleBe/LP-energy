
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
                ':nombre_fenêtre' => $_POST['nombre_fenêtre'],
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

            $query = $dbh->prepare('INSERT INTO window (status, logement_type, nombre_fenêtre, genre, nom, prenom, adresse, code_postal, ville, telephone, email, acceptations, offre) VALUES(:status, :logement_type, :nombre_fenêtre, :homme, :femme, :non_definis, :nom, :prenom, :adresse, :ville, :code_postal, :telephone, :email, :acceptations, :offre)');
            $query->execute($query_params);
            header("location: home-page/redirectory.html");
            exit;

        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            return false;
        }
    }
}

?>

<!DOCTYPE html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <title>EcoConfortSolutions - Fenêtre</title>
    <meta name="description" content="Calculez vos aides de la prime Ma prime renov' pour l'installation de fenêtres. Obtenez une estimation de vos aides financières avec notre formulaire en ligne.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/window.css">
</head>

<body>
<header>
    <div id="title">
        <h1>NOUVELLES AIDES 2023, INSTALLEZ VOS FENÊTRES !</h1>
    </div>

    <div id="txt-form-header">
        <p id="container-text-header">
            RÉPONDEZ A NOTRE <span>QUESTIONNAIRE RAPIDE</span> POUR NOUS PERMETTRE DE VOUS ACCOMPAGNER DANS L'INSTALLATION DE VOS <span>FENÊTRES !</span><br><br>

            DES AIDES <span>(JUSQU’A 250€ PAR FENÊTRE)</span><br><br>
            DES <span>ÉCONOMIES</span> <br><br>
            UNE CONSOMATION <span>RÉDUITE</span>
        </p>
        <div id="form">
            <p>Recevez la meilleure offre pour concrétiser votre projet</p>
            <form action="window.php" method="POST">
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

                <div class="fl">
                    <label for="nombre_fenêtre"></label>
                    <select id="nombre_fenêtre" name="nombre_fenêtre" required>
                        <option value="">→ Nombres de fenêtres à changter chez vous... <sup>*</sup></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="plus_de_10">Plus de 10</option>
                    </select>
                </div>



                <div class="fl exeption">
                    <label for="sexe">Genre : </label required>
                    <input type="radio" id="homme" name="sexe" value="homme">
                    <label for="homme">Homme</label>

                    <input type="radio" id="femme" name="sexe" value="femme">
                    <label for="femme">Femme</label>

                    <input type="radio" id="non-defini" name="sexe" value="non-defini">
                    <label for="non-defini">Non défini</label>
                </div>

                <div class="f2pl">
                    <input type="text" id="nom" name="nom" placeholder="→ Nom *" autocomplete="nom" required>

                    <input type="text" id="prenom" name="prenom" placeholder="→ Prénom *" autocomplete="prenom" required>
                </div>

                <div class="fl">
                    <input type="text" id="adresse" name="adresse" placeholder="→ Adresse" autocomplete="adresse">
                </div>

                <div class="f2pl">
                    <input type="text" id="ville" name="ville" placeholder="→ Ville" autocomplete="ville">

                    <input type="text" id="code_postal" name="code_postal" placeholder="→ Code Postal *" autocomplete="codePostal" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_code_postal'])) { ?>
                        <p id="error1"><?php echo $errors['lenght_postal_code']; ?></p>
                    <?php } ?>
                </div>

                <div class="2fl">
                    <label for="email"></label>
                    <input type="text" id="email" name="email" placeholder="→ Adresse e-mail *" autocomplete="email" required>
                </div>

                <div class="fl">
                    <?php if (isset($errors['preg_email'])) { ?>
                        <p id="error1"><?php echo $errors['preg_email']; ?></p>
                    <?php } ?>
                </div>

                <div class="fl">
                    <label for="telephone"></label>
                    <input type="text" id="telephone" name="telephone" placeholder="→ Numéro de téléphone" autocomplete="phone">
                </div>

                <div class="fl">
                    <?php if (isset($errors['lenght_phone'])) { ?>
                        <p id="error2"><?php echo $errors['lenght_phone']; ?></p>
                    <?php } ?>
                </div>

                <div id="fb">
                    <div class="exeption" id="acceptations">
                        <input type="checkbox" id="cgu" name="cgu">
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
        <img src="img/WindowCasement.gif" alt="un gif d'une fenêtre ouverte pour illustrer le texte" id="first-image">
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
                et respectueux de l'environnement.
            </p>
        </div>
        <img src="img/Window.gif" alt="un gif d'une fenêtre ouverte pour illustrer le texte" id="green-house">
    </div>
    <div class="contant-button">
        <a href="#form"><button>VÉRIFIER MON ÉLIGIBILITÉ</button></a>
    </div>
</div>

<div class="bandeau">
    <h2>TOUT SAVOIR SUR L’INSTALLATION DE FENÊTRES</h2>
</div>

<div class="contenu" id="contenu2">
    <div class="under-containt">
        <img src="img/window-installation.png" alt="illustration d'employers qui installent une fenêtres" id="illustration">
        <div class="txt-prime">
            <h3 id="pac">FENÊTRES</h3>
            <p id="bold">
                L'installation de fenêtres permet d'améliorer l'isolation thermique et acoustique de la maison,
                d'offrir une meilleure sécurité, d'embellir l'aspect extérieur et d'augmenter la valeur de la
                propriété. Les nouvelles fenêtres peuvent donc apporter un confort accru, une économie d'énergie
                et une valeur ajoutée à la maison.
            </p>
            <br><br>
            <ul>
                <li>→ <span class="green-list">Isolation,</span> les nouvelles fenêtres peuvent améliorer l'isolation thermique de la maison, ce qui permet de réduire les pertes de chaleur et donc de faire des économies d'énergie.</li>
                <li>→ <span class="green-list">Confort,</span> les nouvelles fenêtres peuvent également améliorer le confort acoustique en réduisant les bruits extérieurs.</li>
                <li>→ <span class="green-list">Sécurité,</span> les nouvelles fenêtres peuvent offrir une meilleure sécurité grâce à des technologies telles que les vitrages de sécurité ou les serrures renforcées.</li>
                <li>→ <span class="green-list">Esthétique,</span> les nouvelles fenêtres peuvent améliorer l'esthétique de la maison en donnant un aspect plus moderne et plus attrayant.</li>
                <li>→ <span class="green-list">Valeur,</span>l'installation de nouvelles fenêtres peut également augmenter la valeur de la propriété en améliorant son apparence et en augmentant son efficacité énergétique.</li>
            </ul>
            <a href="#form"><button>INSTALLER MES FENÊTRES</button></a>
        </div>
    </div>
</div>

<div class="bandeau">
    <h2>DISPOSITIF DE L’ÉTAT</h2>
    <div id="logo">
        <img src="img/Groupprimerenov.png" alt="logo de Ma prime rénov'">
        <img src="img/CEE-Certificat-Economie-Energie%201cee.png" alt="logo des certificats d'économies d'énergie">
        <img src="img/phast.png" alt="logo de France renov'">
        <img src="img/phast_1.png" alt="logo de RGE eco artisan et logo de RGE QUALIBAT">
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
