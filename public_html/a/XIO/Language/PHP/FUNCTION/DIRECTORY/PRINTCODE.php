<?PHP
function PRINTCODE($code) {
    $html = highlight_string($code, true);
    $html = str_replace("\n", "", $html);
    $rows = explode("<br />", $html);
    $row_num = array();
    $i = 1;
    foreach($rows as $row) {
        if($i < 10) { $i = "0".$i; }
        if($i==1) { $row_num[] = "<tr><td><code style='word-wrap:break-word;'><font color=\"#000000\"><code>$i</code></font>\t$row</code></td></tr>"; }
        if($i!=1) {
            if(is_int($i/2)) { $row_num[] = "<tr bgcolor=\"#F9F9F9\"><td style='word-wrap: break-word;'><code style='word-wrap:break-word;'><font color=\"#000000\">$i</font>\t$row</code></td></tr>"; }
            else { $row_num[] = "<tr><td><code style='word-wrap:break-word;'><font color=\"#000000\">$i</font>\t$row</code></td></tr>"; }
        }
        $i++;
    }
    return "<pre><table style=\"table-layout: fixed;width:100%;\">".implode($row_num)."</table></pre>";
}?>
