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

      $query = $dbh->prepare('INSERT INTO pompe_chaleur(status, chauffage_actuel, logement_type, genre, nom, prenom, adresse, ville, code_postal, telephone, email, acceptations, offre) VALUES(:status, :chauffage_actuel, :logement_type,  :genre, :nom, :prenom, :adresse, :ville, :code_postal, :telephone, :email, :acceptations, :offre)');
      $query->execute($query_params);
      header("location: /home-page/index.html");
      exit;

    } catch (PDOException $e) {
      echo "Erreur: " . $e->getMessage() . "<br>";
      return false;
    }
  }
}

/*CREATE TABLE pompe_chaleur (
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

<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>EcoSolutions - Pompes à chaleurs</title>
  <meta name="description" content="Calculez vos aides de la prime Ma prime renov' pour l'installation d'une pompe à chaleur. Obtenez une estimation de vos aides financières avec notre formulaire en ligne.">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/pompechaleur.css">
</head>

<body>
  <header>
    <div id="title">
      <h1>NOUVELLES AIDES 2023, INSTALLEZ VOTRE POMPE A CHALEUR !</h1>
    </div>

    <div id="txt-form-header">
      <p id="container-text-header">
        RÉPONDEZ A NOTRE <span>QUESTIONNAIRE RAPIDE</span> POUR NOUS PERMETTRE DE VOUS ACCOMPAGNER DANS VOTRE INSTALLATION DE <span>POMPE A CHALEUR</span><br><br>

        DES AIDES <span>(JUSQU’A 10 00€)</span><br><br>
        DES ECONOMIES <span>(JUSQU’A 70%)</span><br><br>
        DES ENERGIES PLUS <span>VERTE</span>
      </p>
      <div id="form">
        <p>Recevez la meilleure offre pour concrétiser votre projet</p>
        <form action="pompechaleur.php" method="POST">
          <div class="f2pl">
            <select id="status" name="status" required>
              <option value="">→ Vous êtes... <sup>*</sup></option>
              <option value="owner">Propiétaire</option>
              <option value="tenant">Locataire</option>
            </select>

            <select id="heating" name="chauffage_actuel" required>
              <option value="">→ Votre chauffage actuel... <sup>*</sup></option>
              <option value="gas">Gaz</option>
              <option value="fuel_oil">Fioul</option>
              <option value="electric">Électrique</option>
              <option value="wood">Bois</option>
              <option value="dual">Dual(électricité + gaz)</option>
              <option value="other_heating">Autre</option>
            </select>
          </div>

          <div class="fl">
            <label for="logement_type"></label>
            <select id="accommodation" name="logement_type" required>
              <option value="">→ Type de logement... <sup>*</sup></option>
              <option value="house">Une maison</option>
              <option value="appartement">Un appartement</option>
              <option value="business premises">Locaux professionnels</option>
              <option value="autre">Autre</option>
            </select>
          </div>

          <div class="fl exeption">
            <label for="genre">Genre : </label required >
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
            <input type="text" id="adresse" name="adresse" placeholder="→ Adresse" autocomplete="adresse">
          </div>

          <div class="f2pl">
            <input type="text" id="ville" name="ville" placeholder="→ Ville" autocomplete="ville">

            <input type="text" id="code_postal" name="code_postal" placeholder="→ codePostal *" utocomplete="codePostal" required>
          </div>

          <div class="fl">
            <?php if (isset($errors['lenght_code_postal'])) { ?>
              <p id="error1"><?php echo $errors['lenght_postal_code']; ?></p>
            <?php } ?>
          </div>

          <div class="fl">
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
            <input type="text" id="telephone" name="telephone" placeholder="→ Numéro de téléphone *" autocomplete="telephone" required>
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

              <div class="fl">
                <?php if (isset($errors['cgu'])) { ?>
                  <p id="error3"><?php echo $errors['cgu']; ?></p>
                <?php } ?>
              </div>

              <br><br>

              <input type="checkbox" id="offre" name="offre">
              <label for="offre">J'accepte de recevoir des offres personnalisées email, téléphone et sms de EcoConfortSolution.fr ainsi que de ses partenaires</label>
            </div>
              <input type="submit" value="VALIDER" id="hinput">
          </div>
      </form>
      </div>
    </div>
  </header>

  <div class="contenu">
    <h2>Faites des économies sur vos factures d’énergie !</h2>
    <div class="under-containt">
      <video autoplay loop>
        <source src="img/house-electricity.mp4" type="video/mp4">
      </video>

      <div class="txt-prime">
        <h3>EN 2023, MA PRIME RÉNOV S’ÉTEND !</h3>
        <p>
          En 2023, Ma Prime Renov' s'étend à TOUS les propriétaires pour vous offrir plus d'avantages avec
          l’installation de votre pompe à chaleur. Cette prime de l'État aide à couvrir les coûts
          d'installation de ce système de chauffage économe en énergie. Les propriétaires bénéficieront
          d'un soutien financier accru pour réduire leur empreinte carbone et améliorer le confort de leur
          maison. Investissez dans une pompe à chaleur dès maintenant pour profiter de Ma Prime Renov' et
          faire des économies tout en protégeant l'environnement.
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
        <img src="img/greenhouse.gif" alt="gif" id="green-house">
    </div>
    <div class="contant-button">
      <a href="#form"><button>VÉRIFIER MON ÉLIGIBILITÉ</button></a>
    </div>
  </div>

  <div class="bandeau">
    <h2>TOUT SAVOIR SUR LES POMPES A CHALEUR</h2>
  </div>

  <div class="contenu" id="contenu2">
    <div class="under-containt">
      <img src="img/pompechaleur.jpg" alt="illustration d'une pompe à chaleur" id="illustration">
      <div class="txt-prime">
        <h3 id="pac">POMPE À CHALEUR </h3>
        <p id="bold">
          Une pompe à chaleur air-eau utilise l'air extérieur pour chauffer l'eau de votre circuit de
          chauffage, en utilisant la compression/détente de fluides frigorigènes.
        </p>
        <br><br>
        <ul>
          <li>→ <span class="green-list">Très efficace</span>, elle produit plus d'énergie qu'elle n'en consomme, avec un rendement énergétique élevé.</li>
          <li>→ <span class="green-list">Économique</span>, elle permet de réaliser des économies sur la facture énergétique grâce à une consommation électrique réduite.</li>
          <li>→ <span class="green-list">Confortable</span>, elle permet de chauffer votre maison de manière homogène et constante, avec une température agréable tout au long de la journée.</li>
          <li>→ <span class="green-list">Écologique</span>, elle réduit l'impact environnemental de votre chauffage en émettant moins de gaz à effet de serre que les systèmes de chauffage traditionnels.</li>
          <li>→ <span class="green-list">Polyvalente</span>, elle peut être utilisée pour le chauffage seul ou la production d'eau chaude, offrant ainsi une solution globale pour votre confort thermique.</li>
          <li>→ <span class="green-list">Silencieuse</span>, elle fonctionne de manière discrète, avec un niveau sonore faible.</li>
          <li>→ <span class="green-list">Une longue durée de vie</span>, elle peut durer plus de 20 ans si elle est bien entretenue, ce qui en fait un investissement durable pour votre maison.</li>
        </ul>
        <a href="#form"><button>OBTENIR MA POMPE À CHALEUR</button></a>
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
