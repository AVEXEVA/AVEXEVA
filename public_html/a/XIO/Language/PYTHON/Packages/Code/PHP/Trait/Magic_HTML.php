<?php
class magic_html {
  	public function DOCTYPE(){return "<!DOCTYPE html>";}
  	public function a($href = NULL, $HTML = NULL){
      return "<a
        class='" . get_class($this) . "'
        id='{$this->__get($this->__get('primary_key'))}'
        href='{$href}'>{$HTML}</a>";
    }
  	public function abbr(){}
  	public function acronym(){}
  	public function address(){}
  	public function applet(){}
  	public function area(){}
  	public function article(){}
  	public function aside(){}
  	public function audio(){}
  	public function b($HTML = NULL){return "<b class='" . get_class($this) . "' id='" . $this->__get($this->__get('primary_key')) . "'>{$HTML}</b>";}
  	public function base(){}
  	public function basefont(){}
  	public function bdi(){}
  	public function bdo(){}
  	public function big(){}
  	public function blockquote(){}
  	public function body($HTML = NULL){return "<body>{$HTML}</body>";}
  	public function br(){}
  	public function button(){}
  	public function canvas(){}
  	public function caption(){}
  	public function center(){}
  	public function cite(){}
  	public function code(){}
  	public function col(){}
  	public function colgroup(){}
  	public function data(){}
  	public function datalist(){}
  	public function dd(){}
  	public function del(){}
  	public function details(){}
  	public function dfn(){}
  	public function dialog(){}
  	public function dir(){}
  	public function div($HTML = NULL){return "<div class='" . get_class($this) . "' id='" . $this->__get($this->__get('primary_key')) . "'>{$HTML}</div>";}
  	public function dl(){}
  	public function dt(){}
  	public function em(){}
  	public function embed(){}
  	public function fieldset(){}
  	public function figcaption(){}
  	public function figure(){}
  	public function font(){}
  	public function footer(){}
  	public function form(){}
  	public function frame(){}
  	public function frameset(){}
  	public function h1(){}
  	public function head(){}
  	public function header(){}
  	public function hr(){}
  	public function html(){}
  	public function i(){}
  	public function iframe(){}
  	public function img(){}
  	public function input(){}
  	public function ins(){}
  	public function kbd(){}
  	public function label(){}
  	public function legend(){}
  	public function li(){}
  	public function link(){}
  	public function main(){}
  	public function map(){}
  	public function mark(){}
  	public function meta(){}
  	public function meter(){}
  	public function nav(){}
  	public function noframes(){}
  	public function noscript(){}
  	public function object(){}
  	public function ol(){}
  	public function optgroup(){}
  	public function option(){}
  	public function output(){}
  	public function p(){}
  	public function param(){}
  	public function picture(){}
  	public function pre(){}
  	public function progress(){}
  	public function q(){}
  	public function rp(){}
  	public function rt(){}
  	public function ruby(){}
  	public function s(){}
  	public function samp(){}
  	public function script(){}
  	public function section(){}
  	public function select(){}
  	public function small(){}
  	public function source(){}
  	public function span($HTML){return "<span class='" . get_class($this) . "' id='" . $this->__get($this->__get('primary_key')) . "'>{$HTML}</span>";}
  	public function strike(){}
  	public function strong(){}
  	public function style(){}
  	public function sub(){}
  	public function summary(){}
  	public function sup(){}
  	public function svg(){}
  	public function table(){}
  	public function tbody(){}
  	public function td(){}
  	public function template(){}
  	public function textarea(){}
  	public function tfoot(){}
  	public function th(){return "<th class='" . get_class($this) . "' id='" . $this->__get($this->__get('primary_key')) . "'>" . get_class($this) . "</th>";}
  	public function thead(){
      $datatypes = $this->__get('datatypes');
      $vars = array_keys($datatypes);
      $s = "<thead><tr>";
      if(is_array($vars) && count($vars) > 0){
        foreach($vars as $v){
          $s .= "<th class='" . $datatypes[$v] . "'>" . $v . "</th>";
        }
      }
      $s .= "</tr><thead>";
      return $s;
    }
  	public function time(){}
  	public function title(){return "<title>{$this->__get($this->__get('name'))}</title>";}
  	public function tr($HTML = NULL){return "<tr class='" . get_class($this) . "' id='" . $this->__get($this->__get('primary_key')) . "'>{$HTML}</tr>";}
  	public function track(){}
  	public function tt(){}
  	public function u(){}
  	public function ul(){}
  	public function var(){}
  	public function video(){}
    public function wbr(){}
}
?>
