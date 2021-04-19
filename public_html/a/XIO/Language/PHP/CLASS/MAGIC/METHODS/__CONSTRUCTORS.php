<?PHP 
NAMESPACE MAGIC\METHODS;
TRAIT __CONSTRUCTORS{
	PUBLIC FUNCTION __CONSTRUCTORS( ){
		$_VARS = PARENT::__SLEEP( );
		IF( COUNT( $_VARS ) > 0 ){
			FOREACH( $_VARS AS $_VAR ){
				IF( 	ISSET( $_VAR[ 'TYPE' ] )
					&&	EXISTS( 'CLASS/DATA/_' . $_VAR[ 'TYPE' ] )
				){
					$CLASS = '\DATA\_' . $_VAR[ 'TYPE' ];
					PARENT::__SET( $_VAR[ 'NAME' ], NEW $CLASS( $_VAR[ 'VALUE' ] ) );
				} ELSEIF( 	ISSET( $_VAR[ 'TYPE' ] ) 
					&&		EXISTS( 'CLASS' . str_replace( '\\', '/', $_VAR[ 'TYPE' ] ) )
				){
					PARENT::__SET( $_VAR[ 'NAME' ], NEW $_VAR[ 'TYPE' ] );
				} ELSE {
					RETURN NEW \ERROR( '__CONSTRUCTORS :: INDETERMINATE VARIABLE : ' . $_VAR[ 'NAME' ] );
				}
			}
		}
	}
}?>