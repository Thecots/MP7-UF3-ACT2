<!-- UNIRSE A PARTIDA -->
<?php
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  };
  if(!isset($_REQUEST['id'])){
    Header('Location: game.search.php');
  };

  $id = $_REQUEST['id'];
  $username = $_REQUEST['username'];

  $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
  $sql = "UPDATE partides SET guest='$username' WHERE id_partida=$id";
  $result=mysqli_query($con, $sql) or exit(mysqli_error($con));

  Header('Location: game.play.php?username='.$username.'&id='.$id.'&state=guest&game=start');
?>
