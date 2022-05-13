<?php
if(!trait_exists('bootstrap_methods')){
  trait bootstrap_methods {
    public function __card($i = FALSE){
			if(method_exists($this, '__js_select_for_entity')){$this->__js_select_for_entity();}
      if(method_exists($this, '__js_save_entity')){$this->__js_save_entity();}
      if(method_exists($this, '__js_delete_entity')){$this->__js_delete_entity();}
			?><div class='card bg-dark'><form action='index.php' method='GET'><?php
				$this->__card_header($i);
				?><ul class="list-group list-group-flush bg-dark">
					<?php
						$this->__list_group_item_entities($i);
						$this->__list_group_items($i);
            if(method_exists($this, '__list_group_item_counts')){$this->__list_group_item_counts($i);}
						if(!$i){
						}
            if(method_exists($this, '__list_group_item_history')){$this->__list_group_item_history($i);}
            if(method_exists($this, '__list_group_item_Comments')){$this->__list_group_item_Comments();}
					?>
				</ul><?php
			?></div><?php
		}
    public function __table_search(){
      ?><div class='row table-search no-gutters' style='padding:10px;height:7.5%;'>
        <div class='col-4' style='text-align:right;padding:5px;'>Search:</div>
        <div class='col-8'><input type='text' name='Search' /></div>
      </div><?php
    }
    public function __table_header(){
      ?><div class='row table-header no-gutters' style='height:5%;'><?php foreach(self::__get('header') as $c){
        ?><div class='col'><?php echo str_replace('_', ' ', $c);?></div><?php
      }?></div><?php
    }
    public function __table_row($odd = FALSE){
			?><div class='row no-gutters table-row <?php echo $odd ? 'odd' : 'even';?>' onClick='select(this);' class_name='<?php echo get_class($this);?>' rel='<?php echo self::__get(self::__get('primary_key'));?>'><?php
        foreach(self::__get('header') as $c){
          ?><div class='col'><?php echo self::__get($c);?></div><?php
        }
      ?></div><?php
		}
    public function __blank_table_row($odd = FALSE){
			?><div class='row no-gutters table-row <?php echo $odd ? 'odd' : 'even';?>' onClick='select(this);' class_name='<?php echo get_class($this);?>' rel='0'><?php
        ?><div class='col'>None</div><?php
      ?></div><?php
		}
    public function __table_data(){
      ?><div class='row table-data no-gutters' style='height:87.5%;'><div class='col'>
        <?php
        $f = '__get_' . self::__get('plural');
        if(!class_exists('Session')){require('cgi-bin/php/classes/Session.php');}
        $Session = new Session();
        $My_User = $Session->__return_User();
        $Entities = $My_User->$f(isset($_GET['Selector']) ? $_GET['Selector'] : array(), isset($_GET['Selecting']) ? $_GET['Selecting'] : array());
        if(count($Entities) == 0){?><div class='col'>&nbsp;</div><?php }
        $odd = false;
        if(count($Entities) > 0){foreach($Entities as $ID=>$Entity){
          $Entity->__table_row($odd);
          $odd = !$odd;
        }}?>
        <?php if(isset($_GET['For'], $_GET['Foreign_Key'])){self::__blank_table_row();}?>
      </div></div><?php
    }
    public function __table(){
      ?><div id='' class='card bg-dark'><form action='index.php' method='GET'><?php
				self::__card_table_header();
        ?><div id='<?php echo self::__get('plural');?>' class='table'><?php
          self::__table_search();
          $this->__table_header();
          $this->__table_data();
        ?></div><?php
			?></div><?php
    }
    public function __card_table_header(){
			?><div class='card-header'><h4><?php $this->__icon();?> <?php
				?><a style='<?php echo strlen(self::__get('Name')) > 18 ? 'font-size:20px;' : NULL;?>' href='index.php'><?php echo self::__get('plural');?></a><?php
			?></h4></div><?php
		}
		public function __card_header($i = FALSE){
			?><div class='card-header'><h4><?php $this->__icon();?> <?php
				if($i){?><input name='Name' placeholder='Untitled' type='text' value='<?php echo strlen($this->__get('Name')) > 0 ? $this->__get('Name') : '';?>' style='width:75% !important;'/><?php }
				else {?><a style='<?php echo strlen(self::__get('Name')) > 18 ? 'font-size:18px;' : NULL;?>' href='index.php?Entity=<?php echo get_class($this);?>&Card=table'><?php echo strlen($this->__get('Name')) > 0 ? $this->__get('Name') : get_class($this) . ' #' . $this->__get('ID');?></a><?php $this->__menu();?><?php }
			?></h4></div><?php
		}
		protected function __menu($options = array()){
			if(is_array($options) && count($options) > 0){
				?><div class="dropdown" style='float:right;'><a href="#" class="btn dropdown-toggle" tabindex="0"><i class="fas fa-ellipsis-h"></i></a><?php
					?><ul class="menu"><?php foreach($options as $option){if(in_array($option, array('Duplicate', 'Delete', 'Create', 'Void', 'Reset', 'Download', 'Upload'))){$this->__menu_option($option);}}?></ul><?php
				?></div><?php
			}
		}
		protected function __menu_space(){?><li class='menu-item'>&nbsp;</li><?php }
		private function __menu_option($action){
			$this->__menu_space();
			$f = '__' . $action;
			?><li class="menu-item">
				<a href="index.php?Entity=<?php echo get_class($this);?>&Action=<?php echo $action;?>&ID=<?php echo $this->__get('ID');?>&Card=select" style='color:black;'><?php Font_Awesome_Icons::getInstance()->$f();?> <?php echo $action;?></a>
			</li><?php
		}
    public function __list_item_last(){
      $cl = get_class($this);
      $f = '__' . $cl;
      ?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'>Last <?php echo str_replace('_', ' ', $cl);?>:</div>
					<div class='col-7'><a href='index.php?Entity=<?php echo get_class($this);?>&<?php echo self::__get('primary_key');?>=<?php echo self::__get(self::__get('primary_key'));?>'>#<?php echo self::__get(self::__get('primary_key'));?></a></div>
				</div>
			</li><?php
    }
    protected	 function __list_group_item($c, $i = FALSE){
			$f = '__' . $c;
			$v = self::__get($c);
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo str_replace('_', ' ', $c);?>:</div>
					<div class='col-7'><?php echo $i ? "<input name='{$c}' value='{$v}' />" : $v;?></div>
				</div>
			</li><?php
		}
    protected	 function __list_group_item_select_auto($c, $i = FALSE){
			$f = '__' . $c;
			$v = self::__get($c);
      $t = self::__geT('table');
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo str_replace('_', ' ', $c);?>:</div>
					<div class='col-7'><?php
          if($i){?><select name='<?php echo $c;?>'>
            <option value=''>Select</option><?php
            $SQL_STRING = " SELECT    [{$c}]
              FROM      [{$t}]
              GROUP BY  [{$t}].[{$c}]
            ;";
            $r = sqlsrv_query(self::__get('connection'), $SQL_STRING, array());
            if($r){while($option = sqlsrv_fetch_array($r)){
              ?><option value='<?php echo $option[$c];?>' <?php echo $v == $option[$c] ? 'selected' : NULL;?>><?php echo $option[$c];?></option><?php
            }}
          ?></select><?php } else {echo $v;}
          ?></div>
				</div>
			</li><?php
		}
    protected	 function __list_group_item_datetime($c, $i = FALSE){
			$f = '__' . $c;
			$v = date('m/d/Y h:i A',strtotime(self::__get($c)));
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo str_replace('_', ' ', $c);?>:</div>
					<div class='col-7'><?php echo $i ? "<input name='{$c}' value='{$v}' />" : $v;?></div>
				</div>
			</li><?php
		}
    public function __list_group_item_image($c, $i = FALSE){
      $f = '__' . $c;
			?><li class="list-group-item bg-dark">
        <div class='row'>
          <div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
          <div class='col-11'><?php echo $c;?>:</div>
        </div>
      </li><?php
			?><li class="list-group-item bg-light"><?php
			if($i){?><input type='file' name='<?php echo $c;?>' /><?php }
			else {?><img src='https://via.placeholder.com/150' width='100%' /><?php }
			?></li><?php
		}
    protected	 function __list_group_item_checkbox($c, $i = FALSE){
			$f = '__' . $c;
			$v = self::__get($c);
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo str_replace('_', ' ', $c);?>:</div>
					<div class='col-7'><input <?php echo $i ? NULL : 'disabled';?> type='checkbox' name='<?php echo $c;?>' <?php echo $v ? 'checked' : NULL;?> /></div>
				</div>
			</li><?php
		}
    protected	 function __list_group_item_block($c, $i = FALSE){
			$f = '__' . $c;
			$v = self::__get($c);
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-11'><?php echo str_replace('_', ' ', $c);?>:</div>
				</div>
			</li><?php
      ?><li class="list-group-item bg-light">
        <div class='row'>
          <div class='col-12'>
            <textarea rows='5' <?php echo isset($_GET['Card']) && $_GET['Card'] == 'select' ? 'disabled' : '';?> name='<?php echo $c;?>'><?php echo $v;?></textarea>
          </div>
        </div>
      </li><?php
		}
		protected function __list_group_item_count($c, $pl){
			$f = '__select_' . $c . '_count';
			$this->$f();
			$f = '__' . $c;
			?><li class="list-group-item bg-dark">
				<div class='row'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo ucwords($pl);?></div>
					<div class='col-7'><a href='index.php?Entity=<?php echo $c;?>&Card=table&Selector[]=<?php echo get_class($this) . '_ID';?>&Selecting[]=<?php echo self::__get('ID');?>'><?php echo self::__get($c . '_count');?></a></div>
				</div>
			</li><?php
		}
    protected function __list_group_item_last($c){
			$f = '__select_' . $c . '_last';
			$this->$f();
			$entity = self::__get($c . '_last');
      $entity->__list_item_last();
		}
    public function __js_slider_NOUI(){
			?><script src="cgi-bin/libraries/slider/slider-noui.js"></script>
			<link href='cgi-bin/libraries/slider/slider-noui.css' rel='stylesheet' /><?php
		}
    public function __js_linked_Toggle(){
			?><script>
			function linked_Toggle(link){
				var rel = $(link).attr('rel');
				$("#" + rel).toggleClass('hidden');
			}
			</script><?php
		}
		public function __list_group_item_slider($c){
			?><li class="list-group-item bg-dark" rel='<?php echo strtolower(get_class($this));?>-<?php echo strtolower($c);?>' onClick='linked_Toggle(this);'>
				<div class='row'>
					<div class='col-4'><?php echo $c;?>:</div>
					<div class='col-8'><input type='text' id='time-<?php echo strtolower($c);?>' name='time-<?php echo strtolower($c);?>' size='3' value='<?php echo parent::__get($c);?>' /></div>
				</div>
			</li>
			<li class="list-group-item bg-dark hidden" id='<?php echo strtolower(get_class($this));?>-<?php echo strtolower($c);?>'>
				<div class='row'>
					<div class='col-12' ><div id='slider-<?php echo strtolower($c);?>' class='slider'></div></div>
				</div>
			</li>
			<script>
			var slider<?php echo $c;?> = document.getElementById('slider-<?php echo strtolower($c);?>');
			var time<?php echo $c;?> = document.getElementById('time-<?php echo strtolower($c);?>');
			noUiSlider.create(slider<?php echo $c;?>, {
				start: 0,
				step:.25,
				range:{
					'min':0,
					'max':8
				}
			});
			slider<?php echo $c;?>.noUiSlider.on('update',function(values, handle){
				time<?php echo $c;?>.value = values[handle];
			});
			</script><?php
		}
    protected function __list_group_item_entity_group($c, $pl, $i = FALSE){
      self::__select_entity($c);
			$f = '__' . $c;
			$v = self::__get($c);
			?><li class="list-group-item bg-dark">
				<div class='row' id='<?php echo get_class($this);?>_<?php echo $pl;?>' rel='<?php echo !is_null($v) ? $v : 0;?>'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo $pl;?>:</div>
					<div class='col-7'><?php self::__list_group_item_entity_group_a($c, $pl, $i);?></div>
				</div>
			</li><?php
		}
    protected function __list_group_item_entity_group_a($c, $pl, $i = FALSE){
      $v = is_array(self::__get($pl)) ? count(self::__get($pl)) : 0;
			?><a href='index.php?Entity=<?php echo $c;?>&Card=table&Selector=<?php echo get_class($this) . '_ID';?>&Selecting=<?php echo self::__get(self::__get('primary_key'));?>'><?php echo $v;?></a><?php
		}
		protected function __list_group_item_entity($c, $pl, $i = FALSE){
      self::__select_entity($c == 'Parent' ? get_class($this) : $c);
			$f = '__' . $c;
			$v = self::__get($c);
			?><li class="list-group-item bg-dark">
				<div class='row' id='<?php echo get_class($this);?>_<?php echo $c;?>' rel='<?php echo !is_null($v) ? $v : 0;?>'>
					<div class='col-1'><?php Font_Awesome_Icons::getInstance()->$f();?></div>
					<div class='col-4'><?php echo $c;?>:</div>
					<div class='col-7'><?php self::__list_group_item_entity_a($c, $pl, $i);?></div>
				</div>
			</li><?php
		}
		protected function __list_group_item_entity_a($c, $pl, $i = FALSE){
			if($i){?><a href='#' onClick="select_for_Entity('<?php echo $c == 'Parent' ? get_class($this) : $c;?>', '<?php echo $c . '_ID';?>');"><?php
        if(is_numeric($this->__get($c)) && $this->__get($c) > 0){
          if(strlen($this->__get($c . '_Name')) > 0){
            echo $this->__get($c . '_Name');
          } else {
            echo $c . ' #' . $this->__get($c . '_ID');
          }
        } else {
          echo 'SELECT ' . strtoupper($c);
        }
      ?></a><?php }
			elseif(is_numeric(self::__get($c))){?><a href='index.php?Entity=<?php echo $c;?>&Card=select&ID=<?php echo $this->__get($c . '_ID');?>'><?php echo strlen($this->__get($c . '_Name')) > 0 ? $this->__get($c . '_Name') : $c . ' #' . $this->__get($c);?></a><?php }
      else {
        echo 'N/A';
      }
		}
  }
}
?>
