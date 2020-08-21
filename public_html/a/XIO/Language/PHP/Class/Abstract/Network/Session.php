<?php
namespace Network;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Session {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $User = NULL;
  protected $Token = NULL;
  protected $Time_Lapse = NULL;
  protected $User_Agent = NULL;
  protected $Refreshed = NULL;
  //Functions
  public function __verify(){
    $Result = $Driver->__select($Database,
      " SELECT  *
        FROM    Session
                LEFT JOIN Token       ON  Session.Token      = Token.ID
                LEFT JOIN Time_Lapse  ON  Session.Time_Lapse = Time_Lapse.ID
        WHERE   Session.User = ?
                AND Token.Alphanumeric = ?
                AND DATEADD(HOUR, 3, Session.Refreshed) >= ?
      ;", array($_SESSION['User_ID'], $_SESSION['Token_Alphanumeric']), date('Y-m-d H:i:s', strtotime('now')));
    return count($Result) > 0;
  }
  public function __insert(){
    if(self::__validate()){
      $this->__set(array(
        'Token' => new Token(),
        'Time_Lapse' => new Time_Lapse(array(
          'Start' => date('Y-m-d H:i:s', strtotime('now'))
        )),
        'User_Agent' => new User_Agent(),
        'Refreshed' => date('Y-m-d H:i:s', strtotime('now'))
      ));
      $this->__get('Token')->__insert();
      $this->__get('Time_Lapse')->__insert();
      $this->__get('User_Agent')->__insert();

    }
  }
}
?>
