<?php

require 'config.php';

$manager = new PersonnageManager($db);

// Si on veut retourner au menu principal
if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('Location: .');
    exit();
}

// Si la session perso existe, on restaure l'objet.
if (isset($_SESSION['perso'])) {
    $perso = $_SESSION['perso'];
}

// Si on a voulu créer un personnage.
if (isset($_POST['creer']) && isset($_POST['name'])) {
    // On crée un nouveau personnage.
    $perso = new Personnage(['name' => $_POST['name'], 'atk' => $_POST['atk']]); 
    
    if (!$perso->nameValide()) {
        $message = 'Le name choisi est invalide.';
        unset($perso);
    }
    elseif ($manager->existsPersonnageByName($perso->getName())) {
        $message = 'Le name du personnage est déjà pris.';
        unset($perso);
    }
    else {
        $manager->insertPersonnage($perso);
    }
}

// Si on a voulu utiliser un personnage.
elseif (isset($_POST['utiliser']) && isset($_POST['name'])) {
    // Si celui-ci existe.
    if ($manager->existsPersonnageByName($_POST['name'])) {
        $perso = $manager->getOnePersonnageByName($_POST['name']);
    }
    // S'il n'existe pas, on affichera ce message.
    else {
        $message = 'Ce personnage n\'existe pas !';
    }
}


// Si on a voulu mettre à jour les points de dégâts
elseif (isset($_POST['changeatk'])) {

    if (!isset($perso)) {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else {
        $perso->setAtk((int) ($_POST['changeatk']));
        $manager->updatePersonnage($perso);

        $message = 'Les points de dégâts ont bien été mis à jour !';
    }
}

// Si on a voulu réinitialiser les points de vie
elseif (isset($_POST['reinitialiserpv'])) {

    if (!isset($perso)) {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else {
        $perso->reinitPv();
        $manager->updatePersonnage($perso);

        $message = 'Les points de vie ont bien été mis à réinitialiser !';
    }
}

// Si on a voulu supprimer le personnage selectionné
elseif (isset($_POST['supprimerperso'])) {

    if (!isset($perso)) {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else {
        $manager->deleteOnePersonnage($perso);

        session_destroy();
        header('Location: .');
        exit();

        $message = 'Le personnage a bien été supprimer !';
    }
}


// Si on a voulu renommer le personnage selectionné
elseif (isset($_POST['renommerperso'])) {

    if (!isset($perso)) {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else {
        $perso->setName((string) ($_POST['rename']));
        $manager->updatePersonnage($perso);

        $message = 'Le personnage a bien été renommer !';
    }
}


// Si on a cliqué sur un personnage pour le attaquer.
elseif (isset($_GET['attaquer'])) {
    if (!isset($perso)) {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    
    else {
        if (!$manager->existsPersonnageById((int) $_GET['attaquer'])) {
            $message = 'Le personnage que vous voulez attaquer n\'existe pas !';
        }
        
        else {
            $persoAAttaquer = $manager->getOnePersonnageById((int) $_GET['attaquer']);
            
            // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode attaquer.
            $retour = $perso->attaque($persoAAttaquer);
            
            switch ($retour) {
                case Personnage::CEST_MOI :
                    $message = 'Voulez-vous vous attaquer vous-même ?';
                    
                    break;
                    
                case Personnage::PERSONNAGE_FRAPPE :
                    $message = 'Le personnage a bien été frappé !';
                    
                    $manager->updatePersonnage($perso);
                    $manager->updatePersonnage($persoAAttaquer);
                    
                    break;
                        
                case Personnage::PERSONNAGE_TUE :
                    $message = 'Vous avez tué ce personnage !';
                    
                    $manager->updatePersonnage($perso);
                    $manager->deleteOnePersonnage($persoAAttaquer);
                    
                    break;
            }
        }
    }
}


?>
            


<!DOCTYPE html>
<html>
<head>
    <title>The Mandalorian : The Game</title>
    <meta charset="utf-8" />
</head>
<body>

    <fieldset>
    <legend>Système</legend>

        <p>Nombre de personnages dans l'Arène : <?= $manager->getCountPersonnages(); ?></p>
        <p>Points de vie/dégâts max : <?= Personnage::MAXLIFE; ?></p>

<?php

        // On a un message à afficher ?
        if (isset($message)) {
            // Si oui, on l'affiche.
            echo '<p><strong>', $message, '</strong></p>';
        }
        // Si on utilise un personnage (nouveau ou pas).
        if (isset($perso)) {
    
?>

        <p><a href="?deconnexion=1">Retour au choix du personnage</a></p>

    </fieldset>


    

    

    
    <fieldset>
    <legend>Mon personnage</legend>
        <p>
            <form action="" method="post">
                Nom : <input type="text" value="<?= $perso->getName() ?>" name="rename" />
                <input type="submit" value="Renommer" name="renommerperso"/>
            </form>
            <br/>
            <form action="" method="post">
                Dégâts : 
                    <input type="number" value="<?= $perso->getAtk() ?>" name="changeatk" min="1" max="<?= Personnage::MAXLIFE; ?>" />
                    <input type="submit" value="Mettre à jour" />
            </form>
            <br/>
            <form action="" method="post">
                Points de vie : <?= $perso->getPv() ?>
                    <input type="submit" value="Réinitialiser les PV" name="reinitialiserpv" />
            </form>
            <br/>
            <form action="" method="post">
            <input type="submit" value="Supprimer le personnage" name="supprimerperso" />
            </form>
        </p>
    </fieldset>
    
    <fieldset>
    <legend>Les adversaires</legend>
        <p>
    
<?php
            $persos = $manager->getAllPersonnagesExcept($perso->getId());
            
            if (empty($persos)) {
                echo 'Personne à attaquer !';
            }
            
            else {
                foreach ($persos as $unPerso) {
?>

                    <a href="?attaquer=<?= $unPerso->getId() ?>"><?= ($unPerso->getName()) ?></a>
                    ( Dégâts : <?= $unPerso->getAtk() ?> / PV : <?= $unPerso->getPv() ?> )
                <br/>

<?php

                }
            }
?>
    
        </p>
    </fieldset>
    
<?php
    
    // if (isset($perso)) { ...
    }
    
    else {

?>

    <form action="" method="post">
        <p>
            <strong>Choix du personnage :</strong>
            <select name="name">
<?php
            $persos = $manager->getAllPersonnages();

            foreach ($persos as $unPerso) {
?>

                <option value="<?= $unPerso->getName() ?>" ><?= $unPerso->getName() ?></option>

<?php
            }
?>    


            </select>
            <input type="submit" value="Utiliser ce personnage" name="utiliser" />
        </p>
    </form>
    <form action="" method="post">
        <p>
            <strong>Créer un nouveau personnage :</strong> &nbsp;
            Nom : <input type="text" name="name" maxlength="20" /> &nbsp;
            Dégâts : <input type="number" name="atk" min="1" max="<?= Personnage::MAXLIFE; ?>" />
            <input type="submit" value="Créer ce personnage" name="creer" />
        </p>
    </form>
        
        
<?php
    
    // if (isset($perso)) {} else { ...
    }
    
?>
        
        
</body>
</html>

        
        
<?php
        
// Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
if (isset($perso)) {
    $_SESSION['perso'] = $perso;
}
        
?>