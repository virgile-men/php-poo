<?php

class PersonnageManager{

    // Instance de PDO
    private $db;

    // Setter de la BDD
    public function setDb(PDO $db) {
        $this->db = $db;
    }

    // Construct de la class
    public function __construct($db) {
        $this->setDb($db);
    }

    // Récupérer tous les personnages
    public function getAllPersonnages() {
        $requete = "SELECT * FROM personnages";
        $stmt = $this->db->query($requete);

        while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $personnages[] = new Personnage($donnees);
        }

        return $personnages;
    }

    // Récupérer tous les personnages sauf 1 selectionné
    public function getAllPersonnagesExcept(int $id){
        $requete = "SELECT * FROM personnages WHERE id <> :id ORDER BY name";
        $q = $this->db->prepare($requete);
        $q->execute([':id' => $id]);
        
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
            $personnages[] = new Personnage($donnees);
        }
        
        return $personnages;
    }

    // Récupérer un personnage selon son ID
    public function getOnePersonnageById($id) {
        $q = $this->db->query('SELECT * FROM personnages WHERE id = '.$id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Personnage($donnees);    
    } 

    // Récupérer un personnage selon son ID
    public function getOnePersonnageByName($name) {
        $q = $this->db->prepare('SELECT * FROM personnages WHERE name = :name');
        $q->execute([':name' => $name]);

        return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    } 

    // Ajouter un personnage
    public function insertPersonnage(Personnage $perso) {
        $q = $this->db->prepare('INSERT INTO personnages(name, atk) VALUES(:name, :atk)');
        $q->bindValue(':name', $perso->getName());
        $q->bindValue(':atk', $perso->getAtk());
        $q->execute();

        $perso->hydrate([
            'id' => $this->db->lastInsertId(),
            'pv' => Personnage::MAXLIFE,
        ]);
    }

    // Supprimer un personnage
    public function deleteOnePersonnage(Personnage $perso) {
        $this->db->exec('DELETE FROM personnages WHERE id = '.$perso->getId());
    }

    // Nombre de personnages créés
    public function getCountPersonnages() {
        return $this->db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }

    // Vérification de l'existence d'un personnage par son nom
    public function existsPersonnageByName($name) {
        $q = $this->db->prepare('SELECT COUNT(*) FROM personnages WHERE name = :name');
        $q->execute([':name' => $name]);

        return (bool) $q->fetchColumn();
    }

    // Vérification de l'existence d'un personnage par son id
    public function existsPersonnageById($id) {
        return (bool) $this->db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$id)->fetchColumn();
    }


    // Update d'un personnage dans la base de données
    public function updatePersonnage(Personnage $perso) {
        $q = $this->db->prepare('UPDATE personnages SET atk = :atk, pv = :pv, name = :name WHERE id = :id');

        $q->bindValue(':name', $perso->getName(), PDO::PARAM_STR);
        $q->bindValue(':atk', $perso->getAtk(), PDO::PARAM_INT);
        $q->bindValue(':pv', $perso->getPv(), PDO::PARAM_INT);
        $q->bindValue(':id', $perso->getId(), PDO::PARAM_INT);

        $q->execute();
    }



}


?>