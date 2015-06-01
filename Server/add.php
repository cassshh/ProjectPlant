<?php
   include("conec.php");
   $link=Conection();
$Sql="INSERT INTO data VALUES ('' , '".$_GET["test"]."' , '' , '' , '')";     
   mysql_query($Sql,$link);
?>
