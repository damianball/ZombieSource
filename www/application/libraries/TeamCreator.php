<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Team.php');

// Factory class for instanciating a team

class TeamCreator{
    private $ci;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getTeamByTeamID($teamid){
        return new Team($teamid);
    }

    // Change to createNewTeam
    // was getNewTeam
    public function createNewTeamWithPlayer($name, $player){
		$this->ci->load->model('Team_model');
        $newTeam = null;
        $this->ci->db->trans_begin();
        try{
            $newTeamID = $this->ci->Team_model->createTeam($name, GAME_KEY);
            $newTeam = new Team($newTeamID);
            $newTeam->addPlayer($player);
            $this->ci->db->trans_commit();
        } 
        catch (PlayerMemberOfTeamException $e){
            $this->ci->db->trans_rollback();
            throw new DatastoreException('Could not create new team: '.$e->getMessage());
        }
        catch (UnexpectedValueException $e){
            $this->ci->db->trans_rollback();
            throw new DatastoreException('Could not create new team: '.$e->getMessage());
        }
        catch (Exception $e){
            $this->ci->db->trans_rollback();
            throw new DatastoreException('Could not create new team: '.$e->getMessage());
        }
        return $newTeam;
    }
}