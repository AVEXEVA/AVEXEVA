<?PHP
NAMESPACE SQL;
CLASS COLUMN EXTENDS \SQL\INDEX {
  //VARIABLES
  PROTECTED $RESOURCE = NULL;
  ///ARGUMENTS
  PROTECTED $ID       = NULL;
  PROTECTED $Name     = NULL;
  PROTECTED $Datatype = NULL;
  PROTECTED $Position = NULL;
  //FUNCTIONS
  ///MAGIC
  PUBLIC FUNCTION __construct( $_ARGS = NULL ){ parent::__construct( $_ARGS ); }
  ///SQL
  PRIVATE FUNCTION __connect( ){ }
}?>
