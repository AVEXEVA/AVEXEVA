<?php
namespace Post;
class Ticket extends Post {
  //Variables
  protected $Session = NULL;
  //SQLdata
  protected $TicketO = NULL;
  protected $TicketDPDA = NULL;
  //Functions
  public function __construct( $Data ){
    parent::__construct( $Data );
    self::SQL();
    if( !is_array( parent::__get( 'TicketO' ) ) || count( parent::__get( 'TicketO' ) ) == 0 ){ return; }
    if( !is_array( parent::__get('TicketDPDA')) || count( parent::__get( 'TicketDPDA' ) ) == 0 ){ self::Insert(); }
    else { self::Update(); }
  }
  private function Reviewing(){
    sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' ),
      "UPDATE nei.dbo.TicketO SET TicketO.Assigned = 6 WHERE TicketO.ID = ?",
      array( parent::__get( 'Session' )->__get( 'POST' )[ 'ID' ] )
    );
  }
  private function Insert(){
    self::Reviewing();
    $start = date("Y-m-d H:i:s",strtotime(parent::__get('TicketO')['TimeRoute']));
    $end = date("Y-m-d H:i:s",strtotime(parent::__get('TicketO')['TimeComp']));
    $hours = substr($end,11,2) - substr($start,11,2);
    $minutes = substr($end,14,2) - substr($start,14,2);
    $total = ($hours * 1) + ($minutes / 60);
    $Parameters = array(
      'ID'                  => parent::__get( 'Session' )->__get( 'POST' )['ID'],
      'CDate'               => parent::__get( 'TicketO' )['CDate'],
      'DDate'               => parent::__get( 'TicketO' )['DDate'],
      'EDate'               => parent::__get( 'TicketO' )['EDate'],
      'fWork'               => parent::__get( 'TicketO' )['fWork'],
      'Job'                 => parent::__get( 'TicketO' )['Job'],
      'Loc'                 => parent::__get( 'TicketO' )['LID'],
      'Elev'                => parent::__get( 'TicketO' )['LElev'],
      'Type'                => parent::__get( 'TicketO' )['Type'],
      'fDesc'               => parent::__get( 'TicketO' )['fDesc'],
      'Who'                 => parent::__get( 'TicketO' )['Who'],
      'fBy'                 => parent::__get( 'TicketO' )['fBy'],
      'TimeRoute'           => parent::__get( 'TicketO' )['TimeRoute'],
      'TimeSite'            => parent::__get( 'TicketO' )['TimeSite'],
      'TimeComp'            => parent::__get( 'TicketO' )['TimeComp'],
      'AID'                 => parent::__get( 'TicketO' )['AID'],
      'DescRes'             => 'Sync Failure due to Halted Script',
      'ClearCheck'          => 0,
      'ClearPR'             => 0,
      'Status'              => 0,
      'Invoice'             => 0,
      'WorkComplete'        => 1,
      'ResolveSource'       => 'TFM-A3.60',
      'Charge'              => 0,
      'downtime'            => 0,
      'Source'              => '',
      'Total'               => $total,
      'Reg'                 => 0,
      'OT'                  => 0,
      'DT'                  => 0,
      'TT'                  => 0,
      'OtherE'              => 0,
      'SMile'               => 0,
      'EMile'               => 0,
      'StartBreak'          => date('Y-m-d 00:00:00.000'),
      'EndBreak'            => date('Y-m-d 00:00:00.000'),
      'TFMCustom1'          => '',
      'TFMCustom2'          => '',
      'TFMCustom3'          => '',
      'TFMCustom4'          => 0,
      'TFMCustom5'          => 0,
      'idRolCustomContact'  => 0,
      'Custom6'             => 0,
      'Custom7'             => 0,
      'Custom8'             => 0,
      'Custom9'             => 0,
      'Custom10'            => 0,
      'WorkOrder'           => '',
      'PriceL'              => 1,
      'Phase'               => 1,
      'WgaeC'               => 0,
      'Cat'                 => 'None',
      'Est'                 => 0,
      'Level'               => parent::__get( 'TicketO' )['Level']
    )
    $keys = array_keys( $Parameters );
    $keys = implode(',', $keys );
    $values = array_fill(0, count( $Parameters ), '?');
    $values = implode(',', $values);
    sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' ),
      "INSERT INTO TicketDPDA({$keys}) VALUES({$values});",
      $Parameters
    );
  }
  private function Update(){

  }
  private function SQL(){
    self::sqlTicketO();
    self::sqlTicketDPDA();
  }
  private function sqlTicketO(){
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' ),
      " SELECT  *
        FROM    TicketO
                LEFT JOIN Emp ON TicketO.fWork = Emp.fWork
        WHERE       TicketO.ID  = ?
                AND Emp.ID      = ?;",
      array( parent::__get( 'Session' )->__get( 'POST' )['ID'], parent::__get( 'Session' )->__get('User')->__get('ID') )
    );
    parent::__set( 'TicketO', sqlsrv_fetch_array( $Resource ) );
  }
  private function sqlTicketDPDA(){
    $Resource = sqlsrv_query(
      parent::__get( 'Session' )->__get( 'Database' ),
      " SELECT  *
        FROM    TicketDPDA
                LEFT JOIN Emp ON TicketDPDA.fWork = Emp.fWork
        WHERE       TicketDPDA.ID = ?
                AND Emp.ID        = ?;",
      array( parent::__get( 'Session' )->__get( 'POST' )['ID'], parent::__get( 'Session' )->__get('User')->__get('ID') )
    );
    parent::__set( 'TicketDPDA', sqlsrv_fetch_array( $Resource ) );
  }
}?>
