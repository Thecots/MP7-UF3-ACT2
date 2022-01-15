<?php
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  };
  if(!isset($_REQUEST['id'])){
    Header('Location: game.search.php');
  };
  $id = $_REQUEST['id'];

  $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
  $sql = "SELECT (guest) FROM partides WHERE id_partida=$id";
  $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
  $reg=mysqli_fetch_array($result);

  if($reg['guest'] != null){
    Header('Location: game.play.php?username='.$_REQUEST['username'].'&id='.$id.'&state=host&game=start');
  };
?>
<!DOCTYPE html>
<html lang="en">
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahorcado</title>
  <link rel="stylesheet" href="./css/global.css">
</head>
<body>
  <main>
    <header>
      <h1 class="searchH1">Buscando rival</h1>
      <div class="buttons">
        <a style="display: none;"></a>
        <a style="display: none;"></a>
        <a href="game.remove.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>">salir</a>
      </div>
    </header>
    <script>
      setTimeout(() => {
        window.location.reload();
      }, 3000);
    </script>
</body>
</html>