<?php

/* middleware */
if(!isset($_REQUEST['username'])){
  Header('Location: signin.php');
}

?>