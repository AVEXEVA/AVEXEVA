<?php
namespace Page;
class Home extends \Page\Template {
  //Variables
  protected $Session = NULL;
  //Functions
  public function __construct( $Args ){
    parent::__construct( $Args );
    ?><div id='Home' class='Page'><?php
      /*self::Attendance();
      self::Departments();
      self::Documents();
      self::Incident();
      self::Locations();
      self::Map();
      self::Privileges();
      self::Profile();
      self::Requisitions();
      self::Routes();
      self::Safety();
      self::Settings();
      self::Testing();
      self::Tickets();
      self::Units();
      self::Users();
      self::Violations();*/
    ?></div><div style='clear:both;'></div><?php
  }
  public function Button($Title, $Icon, $Privilege, $URL){
    if(     $Privilege == true
        ||  parent::__get('Session')->access( $Privilege ) ){?><div class='Button' onClick="document.location.href='<?php echo $URL;?>';"><?php
          ?><div><?php
            ?><div class='Icon'><?php \Icons::getInstance()->$Icon();?></div><?php
            ?><div class='Text'><?php echo $Title;?></div><?php
          ?></div><?php
    ?></div><?php }
  }
  public function Blank(){?><div style='flex: 1 1 100%;'>&nbsp;</div><?php }
  public function Attendance(){self::Button('Attendance', 'Attendance', 'Attendance', 'Attendance.php');}
  public function Departments(){self::Button('Departments', 'Department', 'Department', 'Departments.php');}
  public function Documents(){self::Button('Documents', 'Documents', 'Documents', 'Documents.php');}
  public function Incident(){self::Button('Incident', 'Incident', 'Incident', 'https://docs.google.com/forms/d/1kqijgH7gnxEVwYaobgCn8nbjNFG-vXXpecXMHkqy0GA/viewform?edit_requested=true');}
  public function Locations(){self::Button('Locations', 'Location', 'Location', 'Locations.php');}
  public function Map(){self::Button('Map', 'Map', 'Map', 'Map.php');}
  public function Privileges(){self::Button('Privileges', 'Privilege', 'Privilege', 'Privileges.php');}
  public function Profile(){self::Button('Profile', 'Profile', true, 'User.php?ID=' . parent::__get( 'Session' )->__get('User')->__get('ID'));}
  public function Requisitions(){self::Button('Requisitions', 'Requisitions', 'Requisitions', 'Requisitions.php');}
  public function Routes(){self::Button('Routes', 'Route', 'Route', 'Routes.php');}
  public function Safety(){self::Button('Safety', 'Safety', true, 'Safety.php');}
  public function Settings(){self::Button('Settings', 'Settings', true, 'Settings.php');}
  public function Testing(){self::Button('Testing', 'Testing', 'Testing', 'Testing.php');}
  public function Units(){self::Button('Units', 'Unit', 'Unit', 'Units.php');}
  public function Users(){self::Button('Users', 'User', 'User', 'Users.php');}
  public function Violations(){self::Button('Violations', 'Violation', 'Violations', 'Violations.php');}
  public function Tickets(){self::Button('Tickets', 'Ticket', 'Ticket', 'Tickets.php');}
}
?>
