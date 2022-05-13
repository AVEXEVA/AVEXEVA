<?php
if(!trait_exists('entity_comments')){
  trait entity_comments {
    public function __list_group_item_Comments(){
			?><li class="list-group-item bg-dark" onClick="toggle_Comments();">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->__Comments();?></div>
					<div class='col-11'>Comments:</div>
				</div>
			</li><?php
			$this->__select_Comments();
			if(count(self::__get('Comments')) > 0){foreach(self::__get('Comments') as $Comment){$Comment->__list_group_item();}}
			$Comment = new Comment(array(
				'Entity'		=>	get_class($this),
				'Entity_ID'	=>	self::__get('ID')
			));
			$Comment->__new();?>
			<script>
				function select_Comment(link){
          var rel = $(link).attr('rel');
					if($(link).children('.buttons').length > 0){}
					else {$(link).append("<div class='col-12 buttons'><button type='button' onClick=\"delete_Entity(this, 'Comment', '<?php echo self::__get('primary_key');?>', '" + rel + "');\"><?php Font_Awesome_Icons::getInstance()->__Delete()?> Delete</button></div>")}
				}
				function toggle_Comments(){$(".Comment").each(function(){$(this).toggleClass('hidden');});}
			</script><?php
		}
		public function __select_Comments(){
			$r = sqlsrv_query(self::__get('connection'),
				"	SELECT 	Comment.*
					FROM 		Comment
					WHERE 	Comment.Entity = ?
									AND Comment.Entity_ID = ?
				;", array(get_class($this), self::__get('ID')));
			if(!$r){return array();}
			if(!class_exists('Comment')){require('cgi-bin/php/classes/Comment.php');}
			$Comments = array();
			while($Comment = sqlsrv_fetch_array($r)){$Comments[] = new Comment($Comment);}
			self::__set('Comments', $Comments);
			return TRUE;
		}
  }
}
?>
