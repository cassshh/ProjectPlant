<?php

// Start een sessie
session_start();

// Sluit de sessie
session_destroy();

// Ga terug naar de homepagina
header("location: homepagina.php");

