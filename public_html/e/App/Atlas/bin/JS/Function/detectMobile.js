function detectMobile() {
   if(window.innerWidth < 1024 || window.innerHeight < 768) {
     return true;
   } else {
     return false;
   }
}