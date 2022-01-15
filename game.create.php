<!-- CREAR PARTIDA -->
<?php
  /* middleware */
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  }
  
  $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
  $sql = "INSERT INTO partides VALUES (null,'".date("Y-m-d")."','".$_REQUEST['username']."',NULL,NULL,NULL,NULL,NULL,'host',NULL)";
  $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
  $id=mysqli_insert_id($con);
  Header('Location: game.waiting.php?username='.$_REQUEST['username'].'&id='.$id);
  
?>