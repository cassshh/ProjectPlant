<?php
//start een sessie
session_start();

//sluit de sessie
session_destroy();

//ga terug naar de homepagina
header ("location: homepagina.php");
