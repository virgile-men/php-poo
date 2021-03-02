<?php
class Personnage {

    const MAXLIFE=200;
    public static $compteur=0;

    private $id;
    private $pv;
    private $atk;
    private $name;
    
    // Constante renvoyée par la méthode `attaquer` si on se frappe soi-même.
    const CEST_MOI = 1;
    // Constante renvoyée par la méthode `attaquer` si on a tué le personnage en le frappant.
    const PERSONNAGE_TUE = 2;
    // Constante renvoyée par la méthode `attaquer` si on a bien frappé le personnage.
    const PERSONNAGE_FRAPPE = 3;
  


    // Construct
    // Permet de créer l'objet
    public function __construct(array $donnees) {
        self::$compteur++;
        return $this->hydrate($donnees);
    }
  
    // Hydrate
    // ['name' => "Gandalf", 'atk'=>20, 'pv"=>100]
    public function hydrate (array $donnees) {
        foreach($donnees as $key => $value) {
            $method = 'set'.ucfirst($key); //setName, SetAtk, ...
            if (method_exists($this, $method))
            $this->$method($value); //$this->setName("Gandalf")
        }

    }


    
    // Getters + Setters
    // Garantie l'intégralité des variables
    
    //ID -> Identifiant
    public function getId () {
        return $this->id;
    }
    public function setId (int $id) {
        $this->id=$id;
    }

    //ATK -> Attaque
    public function getAtk() {
        return $this->atk;
    }
    public function setAtk(int $atk) {
        if ($atk >= 0 && $atk <= self::MAXLIFE) {
        $this->atk = $atk;
        }
    }
    
    //NAME -> Nom
    public function getName(){
        return $this->name;
    }
    public function setName(string $name) {
        $this->name = $name;
    }

    //PV -> Vie
    public function getPv(){
        return $this->pv;
    }
    public function setPv(int $pv){
        $this->pv=$pv;
        if($pv < 0) {$this->pv=0;}
        if($pv>self::MAXLIFE) {$this->pv=self::MAXLIFE;}
    }





    // Booléan
    // Vérifier des informations

    // L'utilisateur ne crée pas un personnage sans remplir de nom ?
    public function nameValide() {
        return !empty($this->name);
    }

    // Le personnage est encore en vie ?
    public function is_alive () {
        return $this->pv > 0;
    }


    // Pouvoirs
    // Fonction des différents pouvoirs communs

    // Attaquer
    public function attaque(Personnage $perso) {
        // Si l'utilisateur essaie de ce frapper soi-même
        if ($perso->getId() == $this->id){
            return self::CEST_MOI;
        }

        $perso->setPv( $perso->getPv() - $this->getAtk() );

        //Si l'adversaire est encore en vie, on dit que le personnage a bien été frappé.
        if ($perso->is_alive()) {
            return self::PERSONNAGE_FRAPPE;
        }
        // Sinon, on dit que le personnage a été tué.
        else {
            return self::PERSONNAGE_TUE;
        }
    }  
   
    // Regénérer
    public function regenerer($x) {
        $this->setPv($x);
    }

    // Réinialiser les PV
    public function reinitPv() {
        $this->setPv(self::MAXLIFE);
    }

    // Compteur
    public static function getCompteur() {
        return self::$compteur;
    }



}

?>