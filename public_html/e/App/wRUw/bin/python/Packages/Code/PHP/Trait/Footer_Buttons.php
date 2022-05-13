<?php
if(!trait_exists('footer_buttons')){
  trait footer_buttons {
    public function __footer(){
			$this->__footer_entities();
		}
		public function __footer_entity(){
			?><div class='row table-buttons no-gutters' style='height:100%;'>
			  <?php $this->__button_Back();?>
			  <?php $this->__button_Edit();?>
			  <?php $this->__button_Save();?>
			</div><?php
		}
		public function __footer_entities(){
			?><div class='row table-buttons no-gutters' style='height:100%;'>
			  <?php $this->__button_Back();?>
			  <?php $this->__button_Create();?>
			  <?php $this->__button_Select();?>
			</div><?php
		}
		public function __footer_select(){
			?><div class='row table-buttons no-gutters' style='height:100%;'>
			  <div class='col'><a href='index.php?Action=back'><?php Font_Awesome_Icons::getInstance()->__Back();?> Back</a></div>
			  <div class='col'><a href='#' onClick='select_Entity();'><?php Font_Awesome_Icons::getInstance()->__Create();?> Select</a></div>
			</div>
			<script>
			function select_Entity(){
			 	var Page = '<?php echo isset($_GET['Page']) ? $_GET['Page'] : '';?>';
			 	var ID = $(".select").attr('rel');
			 	document.location.href='index.php?Page=<?php echo $_GET['For'];?>&<?php echo $_GET['Foreign_Key'];?>=' + ID + '<?php
			 	if(isset($_GET['Keys'])){
			 		$Parameters = '';
			    foreach($_GET['Keys'] as $index=>$Key){
            $Parameters .= "&{$Key}={$_GET['Values'][$index]}";
          }
          echo $Parameters;
        }?>';
			}<?php
		}
    public function __button_Save(){?><div class='col footer-button <?php echo isset($_GET['Card']) && ($_GET['Card'] == 'edit' || $_GET['Card'] == 'insert') ? '' : 'hidden';?>' rel='save'><a onClick="save_Entity(this);" href='#'><?php Font_Awesome_Icons::getInstance()->__Save();?> Save</a></div><?php }
		public function __button_Back(){?><div class='col'><a href='index.php?Action=back'><?php Font_Awesome_Icons::getInstance()->__Back();?> Back</a></div><?php }
		public function __button_Edit(){?><div class='col footer-button <?php echo isset($_GET['Card']) && ($_GET['Card'] == 'edit' || $_GET['Card'] == 'insert') ? 'hidden' : '';?>' rel='edit'><a href='index.php?Entity=<?php echo get_class($this);?>&ID=<?php echo self::__get('ID');?>&Card=edit'><?php Font_Awesome_Icons::getInstance()->__Edit();?> Edit</a></div><?php }
		public function __button_Create(){?><div class='col footer-button' rel='create'><a href='index.php?Entity=<?php echo get_class($this);?>&Card=insert&<?php if(isset($_GET['Selector'],$_GET['Selecting'])){?><?php echo reset($_GET['Selector']);?>=<?php echo reset($_GET['Selecting']);?><?php }?>'><?php Font_Awesome_Icons::getInstance()->__Create();?> Create</a></div><?php }
    public function __js_select(){
      ?><script>
        var keyheld = '';
        $(document).ready(function(){
          window.addEventListener('keydown', keydownfunc, false);
          window.addEventListener('keyup', keyupfunc, false);
        });
        function keydownfunc(e){
          keyheld = e.code;
        }
        function keyupfunc(e){
          keyheld = '';
        }
        function select(link){
          if(keyheld == 'ControlLeft'){
            $(link).addClass('select');
          }
          else {
            $(link).siblings('.select').each(function(){
              $(this).removeClass('select');
            });
            $(link).toggleClass('select');
          }
          if($(link).hasClass('select') || $(link).siblings('.select').length > 0){
            $(".col[rel='create']").addClass('hidden');
            $(".col[rel='select']").removeClass('hidden');
          } else {
            $(".col[rel='create']").removeClass('hidden');
            $(".col[rel='select']").addClass('hidden');
          }
        }
        <?php if(isset($_GET['For'])){?>function select_Entity(){
          var Page = '<?php echo isset($_GET['Page']) ? $_GET['Page'] : '';?>';
          var ID = $(".select").attr('rel');
          document.location.href='index.php?Entity=<?php echo $_GET['For'];?>&<?php echo $_GET['Foreign_Key'];?>=' + ID + '<?php
            if(isset($_GET['Keys'])){
              $Parameters = '';
              foreach($_GET['Keys'] as $index=>$Key){
                if($_GET['Foreign_Key'] == $Key){continue;}
                $Parameters .= "&{$Key}={$_GET['Values'][$index]}";
              }
              echo $Parameters;
            }
          ?>';
        }<?php } else {?>function select_Entity(){document.location.href='index.php?Entity=' + $('.table-data>div>.select').attr('class_name') + '&ID=' + $('.table-data>div>.select').attr('rel') + '&Card=select';}<?php }?>
      </script><?php
    }
		public function __button_Select(){
      self::__js_select();
      ?><div class='col footer-button hidden' rel='select'><a onClick="select_Entity();" href='#'><?php Font_Awesome_Icons::getInstance()->__Create();?> Select</a></div><?php }
  }
}
?>
