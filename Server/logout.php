<?php
session_start();
session_destroy();

header ("location: http://plant.serverict.nl/homepagina.php");
