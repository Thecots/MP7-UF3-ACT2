<?php
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  };
  if(!isset($_REQUEST['id'])){
    Header('Location: game.search.php');
  };
  $partida = $_REQUEST['id'];
  $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
  $sql = "DELETE FROM partides WHERE id_partida=$partida";
  echo $sql;
  $result=mysqli_query($con, $sql) or exit(mysqli_error($con));

  Header('Location: game.search.php?username='.$_REQUEST['username']);
?>