<?php


App::uses('AppModel', 'Model');
App::uses('File', 'Utility');

class Fighter extends AppModel
{

    public $displayField = 'name';
    public $belongsTo = array(

        'User' => array(
            'className' => 'User',
            'foreignKey' => 'player_id',
        ),

    );

    public $validate = array(
        'name' => array(
            'unique' => array(
                'rule' => array('isUnique'),
                'message' => 'Nom deja utilise'

            ), 'max' => array('rule' => array('maxLength', 16)),
            'min' => array('rule' => array('minLength', 3)),
            'alphaNum' => array('rule' => 'alphanumeric'),


        ), 'required' => array(
            'rule' => 'notBlank',
            'message' => 'Un nom est requis'
        )

    );

    /*
     *Méthode de récupération de tout les combattant d'un joueur
     *Reçoit un id de User en string et retourne un array contenant tout ses Fighter en array
     */
    public function getFightersByUser($user_id)
    {
        return $this->find('all', array('conditions' => array('player_id' => $user_id)));
    }

    /*
     *Méthode de récupération d'un combattant par son nom et l'id de son joueur
     *Reçoit un nom de Fighter et un id de User en string et retourne un combattant en array
     */
    public function getFighterByUserAndName($user_id, $name)
    {
        return $this->find('first', array('conditions' => array('player_id' => $user_id, 'Fighter.name' => $name)));
    }

    /*
     *Méthode de récupération d'un combattant par son nom
     *Reçoit un nom de Fighter en string et retourne un combattant
     */
    public function getFighterByName($name)
    {
        return $this->findByName($name);
    }

    /*
     *Méthode de récupération de la liste des noms des combattants d'un joueur
     *Reçoit un id de User en string et retourne un array listant les noms de ses combattans
     */
    public function getFighterNameByUser($user_id)
    {
        $result = array();
        $fighters = $this->find('all', array('conditions' => array('player_id' => $user_id)));

        foreach ($fighters as $fighter) {
            $result = array_merge($result, array($fighter['Fighter']['name'] => $fighter['Fighter']['name']));
        }
        return $result;
    }

    /*
     *Methode de création de vecteur en fonction d'une direction
     *Reçoit une direction en string et retourne un array contenant deux entiers x et y
     */
    public function vector($direction)
    {
        $vector = array('x' => 0, 'y' => 0);

        switch ($direction) {
            case "north":
                $vector['y']--;
                break;
            case "south":
                $vector['y']++;
                break;
            case "east":
                $vector['x']++;
                break;
            case "west":
                $vector['x']--;
                break;
            default:
                ;
        }

        return $vector;
    }

    /*
     *Méthode déterminant si un combattant est sur une case ciblé par un autre
     *Reçoit un Fighter en action en array et la direction de celle-ci en string
     *Retourne -1 si la case cible est hors de l'arène, 0 si la case est vide et retourne le combattant sur la case si elle est occupée
     */
    public function isThere($fighter, $vector)
    {
        $player = $fighter;
        $target = array();
        $result = -1;

        //Vérification que la case cible est dans l'arène
        if ((($player['Fighter']['coordinate_x'] + $vector['x']) >= 0) &&
            (($player['Fighter']['coordinate_x'] + $vector['x']) < MAPLIMITX) &&
            (($player['Fighter']['coordinate_y'] + $vector['y']) >= 0) &&
            (($player['Fighter']['coordinate_y'] + $vector['y']) < MAPLIMITY)
        ) {
            //Vérification de la présence d'un combattant sur la case cible
            $target = $this->find('all', array('conditions' => array('coordinate_x' => ($player['Fighter']['coordinate_x'] + $vector['x']), 'coordinate_y' => ($player['Fighter']['coordinate_y'] + $vector['y']))));
        } else $result = -2;

        //Vérification du résultat de l'appel $this->find
        if (count($target) == 0) $result++;
        else $result = $target[0];

        return $result;
    }

    /*
     *Méthode action de déplacement d'un combattant
     *Reçoit un Fighter en array et une direction en string
     *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
     */
    public function doMove($fighter, $direction)
    {
        //Initialisation de l'Event
        $event = array('name' => '', 'coordinate_x' => 0, 'coordinate_y' => 0);

        //Récupération du Fighter en action
        $player = $fighter;
        //Mention du Fighter en action dans l'Event
        $event['name'] .= $player['Fighter']['name'] . " se deplace ";
        //Détermination du vecteur mouvement à partir de la direction choisi par le joueur
        $vector = $this->vector($direction);
        //Ajout de la case cible dans l'Event
        $event['coordinate_x'] = $player['Fighter']['coordinate_x'] + $vector['x'];
        $event['coordinate_y'] = $player['Fighter']['coordinate_y'] + $vector['y'];

        //Vérification de la présence d'un autre Fighter à la destination
        if ($this->isThere($player, $vector) == 0) {
            //Enregistrement du mouvement en DB
            $datas = array('Fighter' => array('id' => $player['Fighter']['id'], 'coordinate_y' => $player['Fighter']['coordinate_y'] + $vector['y'], 'coordinate_x' => $player['Fighter']['coordinate_x'] + $vector['x']));
            $this->save($datas);
            //Mention de l'échec de l'action dans l'Event
        } else $event['name'] .= 'mais est bloque';

        return $event;
    }

    /*
     *Méthode action d'attaque d'un combattant
     *Reçoit un Fighter en array et une direction en string
     *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
     */
    public function doAttack($fighter, $direction)
    {
        //Initialisation de l'Event
        $event = array('name' => '', 'coordinate_x' => 0, 'coordinate_y' => 0);

        //Récupération du Fighter en action
        $player = $fighter;
        //Mention du Fighter en action dans l'Event
        $event['name'] .= $player['Fighter']['name'] . " attaque ";
        //Détermination du vecteur d'attaque à partir de la direction choisi par le joueur
        $vector = $this->vector($direction);
        //Ajout de la case cible dans l'Event
        $event['coordinate_x'] = $player['Fighter']['coordinate_x'] + $vector['x'];
        $event['coordinate_y'] = $player['Fighter']['coordinate_y'] + $vector['y'];

        //Vérification de la présence d'un Fighter sur la case cible
        $defenser = $this->isThere($player, $vector);

        //Si un Fighter est trouvé comme "Attaqué"
        if (is_array($defenser)) {
            $result = true;

            //Jet de tentative d'attaque
            $rand = rand(1, 20);
            // echo $rand;

            //Mention du Fighter attaqué dans l'event
            $event['name'] .= $defenser['Fighter']['name'] . " et le ";

            //Si le jet d'attaque est supérieur à 10 plus la différence de niveau des deux joueurs, l'attaque réussie
            if ($rand > (10 + $defenser['Fighter']['level'] - $player['Fighter']['level'])) {
                //Enregistrement de la blessure en DB
                $defenser['Fighter']['current_health'] -= $player['Fighter']['skill_strength'];
                $datas = array('Fighter' => array('id' => $defenser['Fighter']['id'], 'current_health' => $defenser['Fighter']['current_health']));
                $this->save($datas);

                //Mention de la réussite de l'attaque dans l'Event, détermination de la survie de la victime et attribution de l'xp
                if ($this->isDead($defenser)) {
                    $event['name'] .= "tue";
                    $xp = ($defenser['Fighter']['level'] - $player['Fighter']['level']);
                    if ($xp <= 0) $xp = 1;
                    else $xp++;
                    $player['Fighter']['xp'] += $xp;
                } else {
                    $event['name'] .= "touche";
                    $player['Fighter']['xp']++;
                }

                //Sauvegarde du gain d'xp
                $datas = array('Fighter' => array('id' => $player['Fighter']['id'], 'xp' => $player['Fighter']['xp']));
                $this->save($datas);
                //Mention de l'échec de l'attaque dans l'Event
            } else $event['name'] .= "rate";
        } else $event['name'] .= "dans le vide";

        return $event;
    }

    /*
     *Méthode de création d'un nouveau combattant
     *Reçoit un id de User et un nom de Fighter en string
     *Retourne un Event en array avec des valeurs nom, coordinate_x et coordinate_y initialisées
     */
    public function spawn($userId, $name,$surroundings)
    {
        //Initialisation de l'Event
        $event = array('name' => 'Entree de ', 'coordinate_x' => 0, 'coordinate_y' => 0);

        //Mention du nouveau Fighter dans l'Event
        $event['name'] .= $name;

        //Initialisation des coordonnées de spawn
        $coord = array('coordinate_x' => 0, 'coordinate_y' => 0);
		$surroundingBlocks = array();
        $tried = array();
        $freeSpot = false;
		//var_dump($surroundings);
		
		foreach($surroundings as $surrounding){
			if(($surrounding['Surroundings']['type'] == 'trap')||($surrounding['Surroundings']['type'] == 'wall')||($surrounding['Surroundings']['type'] == 'mob')){
				array_push($surroundingBlocks,array('coordinate_x'=>$surrounding['Surroundings']['coordinate_x'],'coordinate_y'=>$surrounding['Surroundings']['coordinate_y']));
			}
		}

        //Temps qu'un emplacement libre n'est pas trouvé
        while (!$freeSpot) {
            //Choix d'un couple (x,y) de coordonnée aléatoire dans l'arène
            $coord['coordinate_x'] = rand(0, MAPLIMITX - 1);
            $coord['coordinate_y'] = rand(0, MAPLIMITY - 1);

            //Si la case (x,y) n'a pas été testée
            if (array_search($coord, $tried) == false) {
                //Si aucun Fighter n'est positionné sur la case (x,y), la case est marquée comme libre
                if ((count($this->find('all', array('conditions' => array('coordinate_x' => $coord['coordinate_x'], 'coordinate_y' => $coord['coordinate_y'])))) == 0)&&(array_search($coord, $surroundingBlocks) == false)) $freeSpot = true;
                //Sinon la case est marquée comme testée
                else array_push($tried, $coord);
            }
            //Si toute les cases ont été testées
            if (count($tried) == (MAPLIMITX * MAPLIMITY)) {
                //L'Event est annulé et la boucle est terminée
                $event = null;
                break;
            }
        }

        //Si la dernière case testée est marquée libre
        if ($freeSpot) {
            //Enregistrement du noveau Fighter
            $datas = array('Fighter' => array('player_id' => $userId,
                'name' => $name,
                'coordinate_x' => $coord['coordinate_x'],
                'coordinate_y' => $coord['coordinate_y'],
                'level' => 1,
                'xp' => 0,
                'skill_sight' => 1,
                'skill_strength' => 1,
                'skill_health' => 3,
                'current_health' => 3
            ));
            $this->save($datas);
            //Ajout de la l'emplacement du spawn dans l'Event
            $event['coordinate_x'] = $coord['coordinate_x'];
            $event['coordinate_y'] = $coord['coordinate_y'];
        }
        return $event;
    }

    /*
     * Méthode de vérification si un combattant est en vie
     *Reçoit un combattant et retourne un booléen
     */
    public function isDead($fighter)
    {
        $result = false;

        //Si les HP du Fighter sont en dessous de 1, le combattant est supprimé et true est retourné
        if ($fighter['Fighter']['current_health'] <= 0) {
            $this->delete($fighter['Fighter']['id']);
            $result = true;
        }

        return $result;

    }

    public function kill($fighter)
    {
        $file = new File(WWW_ROOT . 'img\\' . $fighter['Fighter']['id'] . '.jpg', false);
        if (!$file) $file = new File(WWW_ROOT . 'img\\' . $fighter['Fighter']['id'] . '.jpeg', false);
        if (!$file) $file = new File(WWW_ROOT . 'img\\' . $fighter['Fighter']['id'] . '.png', false);
        if (!$file) $file = new File(WWW_ROOT . 'img\\' . $fighter['Fighter']['id'] . '.gif', false);
        if ($file) $file->delete();
        $this->delete($fighter['Fighter']['id']);
    }

    public function killMob($fighter, $direction)
    {
        //Initialisation de l'Event
        $event = array('name' => '', 'coordinate_x' => 0, 'coordinate_y' => 0);
        $vector = $this->vector($direction);

        $player = $fighter;

        //Mention du Fighter en action dans l'Event
        $event['name'] .= $player['Fighter']['name'] . " tue le monstre ";

        //Ajout de la case cible dans l'Event
        $event['coordinate_x'] = $player['Fighter']['coordinate_x'] + $vector['x'];
        $event['coordinate_y'] = $player['Fighter']['coordinate_y'] + $vector['y'];

        //Attribution de l'xp pour le meurtre du monstre
        $player['Fighter']['xp'] += XPUP;
        $datas = array('Fighter' => array('id' => $player['Fighter']['id'], 'xp' => $player['Fighter']['xp']));
        $this->save($datas);

        return $event;
    }

    /*
     *Méthode de vérification si un combattant peu monter de niveau
     *Reçoit un combattant et retourne un booléen
     */
    public function canLevelUp($fighter)
    {
        $result = false;

        if ($fighter['Fighter']['xp'] >= XPUP) $result = true;

        return $result;
    }

    /*
     *Méthode de passage de niveau d'un combattant
     *Reçoit un combattant et une stat à améliorer et retourne le Fighter modifié
     */
    public function levelUp($fighter, $stat)
    {

        //Si le Fighter à XPUP d'xp au moins
        if ($fighter['Fighter']['xp'] >= XPUP) {

            //Retrait de XPUP d'xp, incrément du level, amélioration d'une stat et remise au max des HP
            $fighter['Fighter']['xp'] -= XPUP;
            $fighter['Fighter']['level']++;
            $fighter['Fighter']['skill_' . $stat]++;
            $fighter['Fighter']['current_health'] = $fighter['Fighter']['skill_health'];

            //Sauvegarde des nouvelles caractéristiques
            $datas = array('Fighter' => array('id' => $fighter['Fighter']['id'],
                'xp' => $fighter['Fighter']['xp'],
                'level' => $fighter['Fighter']['level'],
                'skill_' . $stat => $fighter['Fighter']['skill_' . $stat],
                'current_health' => $fighter['Fighter']['current_health']
            )
            );
            $this->save($datas);
        }

        return $fighter;
    }

    public function getEnnemiesInRange($coord, $range)
    {

        $res = array();
        for ($x = 0; $x <= $range; $x++) {
            $y = $range - $x;
            for ($y; $y >= 0; $y--) {
                $fighter = $this->getFighterByPosition($x + $coord['coord_x'], $y + $coord['coord_y']);
                if (count($fighter) != 0) array_push($res, $fighter[0]['Fighter']);
                $fighter = $this->getFighterByPosition($x + $coord['coord_x'], -$y + $coord['coord_y']);
                if (count($fighter) != 0) array_push($res, $fighter[0]['Fighter']);
                $fighter = $this->getFighterByPosition(-$y + $coord['coord_x'], $x + $coord['coord_y']);
                if (count($fighter) != 0) array_push($res, $fighter[0]['Fighter']);
                $fighter = $this->getFighterByPosition(-$y + $coord['coord_x'], -$x + $coord['coord_y']);
                if (count($fighter) != 0) array_push($res, $fighter[0]['Fighter']);
            }
        }
        $res = array_map("unserialize", array_unique(array_map("serialize", $res)));
        $resultat = array();
        foreach ($res as $case) {
            array_push($resultat, $case);
        }
        return $resultat;
    }

    public function getFighterByPosition($x, $y)
    {
        $try = $this->find('all', array('conditions' => array('coordinate_x' => $x, 'coordinate_y' => $y)));
        return $try;
    }

}

?>