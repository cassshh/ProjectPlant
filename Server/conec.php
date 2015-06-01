<?php
function Conection(){
   if (!($link=mysql_connect("plant.serverict.nl","plant","$_Tan1900")))  {
      exit();
   }
   if (!mysql_select_db("plant_data",$link)){
      exit();
   }
   return $link;
}
?>