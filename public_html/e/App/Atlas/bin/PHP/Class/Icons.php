<?php
if(!class_exists('Singleton')){require('bin/PHP/Class/Singleton.php');}
class Icons extends Singleton {
  //Magic Methods
  public function __call($name, $arguments){
    if(method_exists($this, $name)){ $this->$name(); }
    else {self::Default($arguments);}
  }
  /*//////////////////////Icon Functions/////////////////////*/
  //General Use
  public function Default($Size=null){?><i class="fa fa-link fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Vanilla
  public function Archive($Size=null){?><i class="fa fa-archive fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Book($Size=null){?><i class="fa fa-book fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Cogs($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Dollar($Size=null){?><i class="fa fa-dollar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Info($Size=null){?><i class="fa fa-info fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Phone($Size=null){?><i class="fa fa-phone fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Create($Size=null){?><i class="fa fa-plus fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Attributes
  ///Basic
  public function ID($Size=null){?><i class="fa fa-info fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Description($Size=null){?><i class="fa fa-paragraph fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  ///Address
  public function Address($Size=null){?><i class="fa fa-map-signs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Street($Size=null){?><i class="fa fa-map-signs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function City($Size=null){?><i class="fa fa-map-signs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function State($Size=null){?><i class="fa fa-map-signs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Zip($Size=null){?><i class="fa fa-map-signs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Objects
  public function Connection($Size=null){?><i class="fa fa-exchange fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Customer($Size=null){?><i class="fa fa-link fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Delivery($Size=null){?><i class="fa fa-truck fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i></i><?php }
  public function Elevator($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Escalator($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Job($Size=null){?><i class="fa fa-suitcase fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Requisition($Size=null){?><i class="fa fa-barcode fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Requisitions($Size=null){?><i class="fa fa-cubes fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Route($Size=null){?><i class="fa fa-road fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Support($Size=null){?><i class="fa fa-users fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Ticket($Size=null){?><i class="fa fa-ticket fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Unit($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function User($Size=null){?><i class="fa fa-user fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Users($Size=null){?><i class="fa fa-users fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Violation($Size=null){?><i class="fa fa-warning fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Pages
  public function Home($Size=null){?><i class="fa fa-home fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Map($Size=null){?><i class="fa fa-map fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Profile($Size=null){?><i class="fa fa-user fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Sitemap($Size=null){?><i class="fa fa-sitemap fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Other
  public function Accounting($Size=null){?><i class="fa fa-dollar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Code($Size=null){?><i class="fa fa-code fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Dispatch($Size=null){?><i class="fa fa-headphones fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function HR($Size=null){?><i class="fa fa-child fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Field($Size=null){?><i class="fa fa-bolt fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Safety($Size=null){?><i class="fa fa-exclamation fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Form
  public function Check($Size=null){?><i class="fa fa-check fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  //Redirects
  public function Information( $Size = null ){ self::Info( $Size ); }


  //Messy Functions








  public function Location($Size=null){?><i class="fa fa-building fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }




  public function Service($Size=null){?><i class="fa fa-phone fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Review($Size=null){?><i class="fa fa-book fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Hours($Size=null){?><i class="fa fa-hourglass-half fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Admin($Size=null){?><i class="fa fa-eye fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  public function Update($Size=null){?><i class="fa fa-compass fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Payroll($Size=null){?><i class="fa fa-money fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Timesheet($Size=null){?><i class="fa fa-clock-o fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Time($Size=null){?><i class="fa fa-clock-o fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  public function Invoice($Size=null){?><i class="fa fa-stack-overflow fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Activities($Size=null){?><i class="fa fa-feed fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Timeline($Size=null){?><i class="fa fa-feed fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Collection($Size=null){?><i class="fa fa-dollar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  public function Purchasing($Size=null){?><i class="fa fa-dollar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Proposal($Size=null){?><i class="fa fa-folder-open fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Search($Size=null){?><i class="fa fa-search fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Contract($Size=null){?><i class="fa fa-pencil fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Financial($Size=null){?><i class="fa fa-bar-chart-o fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Refresh($Size=null){?><i class="fa fa-refresh fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Pnl($Size=null){?><i class="fa fa-dollar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Portal($Size=null){?><img src='media/images/icons/portal.png' width='100%' height='auto' /><?php }
  public function Chart($Size=null){?><i class="fa fa-bar-chart-o fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Maintenance($Size=null){?><i class="fa fa-wrench fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Printer($Size=null){?><i class="fa fa-print fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Division($Size=null){?><i class="fa fa-sitemap fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Department($Size=null){?><i class="fa fa-sitemap fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function DOB($Size=null){?><i class="fa fa-eye fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Legal($Size=null){?><i class="fa fa-legal fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Modernization($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Testing($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Test($Size=null){?><i class="fa fa-clipboard fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Documents($Size=null){?><i class="fa fa-clipboard fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Processing($Size=null){?><i class="fa fa-marker fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Inspection($Size=null){?><i class="fa fa-cogs fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Add($Size=null){?><i class="fa fa-plus-circle fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Edit($Size=null){?><i class="fa fa-edit fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Delete($Size=null){?><i class="fa fa-trash fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Save($Size=null){?><i class="fa fa-save fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Dashboard($Size=null){?><i class="fa fa-dashboard fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Calendar($Size=null){?><i class="fa fa-calendar fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Calendar_Plus($Size=null){?><i class='fa fa-calendar-plus-o fa-fw'></i><?php }
  public function Clock($Size=null){?><i class='fa fa-clock-o fa-fw'></i><?php }
  public function Note($Size=null){?><i class='fa fa-sticky-note-o fa-fw'></i><?php }
  public function Territory($Size=null){?><i class="fa fa-black-tie fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Sales($Size=null){?><i class='fa fa-black-tie fa-fw'></i><?php }
  public function Operations($Size=null){?><i class='fa fa-cogs fa-fw'></i> <?php }
  public function Table($Size=null){?><i class='fa fa-table fa-fw'></i> <?php }
  public function Attendance($Size=null){self::Calendar($Size);}
  public function Settings($Size = null){self::Unit($Size);}
  public function Notification($Size = NULL){?><i class='fa fa-bell fa-fw fa-<?php echo !is_null($Size) ? $Size : 1;?>x'></i><?php }
  public function Incident($Size = NULL){?><i class='fa fa-bell fa-fw fa-<?php echo !is_null($Size) ? $Size : 1;?>x'></i><?php }
  public function GoogleDrive($Size = NULL){self::Archive($Size); }
  public function Website($Size=null){?><i class='fa fa-external-link fa-fw'></i> <?php }
  public function List1($Size=null){?><i class="fa fa-list fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Birthday($Size=null){?><i class="fa fa-birthday-cake fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function History($Size=null){?><i class="fa fa-history fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Repair($Size=null){?><i class="fa fa-wrench fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Controls($Size=null){?><i class="fa fa-user-secret fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Email($Size=null){?><i class="fa fa-envelope fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Back($Size=null){?><i class="fa fa-arrow-left"></i><?php }
  public function Print1($Size=null){?><i class="fa fa-print fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Bug($Size=null){?><i class="fa fa-bug fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }

  public function Blank($Size=null){?><i class="fa fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Resident($Size=null){?><i class="fa fa-flag fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Logout($Size=null){?><i class="fa fa-sign-out fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Login($Size=null){?><i class="fa fa-sign-in fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Log($Size=null){?><i class="fa fa-book fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Action($Size=null){?><i class="fa fa-at fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Web($Size=null){?><i class="fa fa-bookmark fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Purchase($Size=null){?><i class="fa fa-shopping-cart fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Privilege($Size=null){?><i class="fa fa-user-secret fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Report($Size=null){?><i class="fa fa-paperclip fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Github($Size=null){?><i class="fa fa-github fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Paragraph($Size=null){?><i class="fa fa-paragraph fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
  public function Buttons($Size=null){?><i class="fa fa-codepen fa-fw fa-<?php if(!is_null($Size)){echo $Size;?>x<?php }?>"></i><?php }
}?>
