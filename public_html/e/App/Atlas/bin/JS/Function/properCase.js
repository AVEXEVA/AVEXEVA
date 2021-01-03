function properCase(s){
    return s.replace(/([^\s:\-])([^\s:\-]*)/g,function($0,$1,$2){
        return $1.toUpperCase()+$2.toLowerCase();
    });
}