<!-- BUSCAR PARTIDA, BOTÓN JUGAR VS IA, BOTÓN CREAR PARTIDA-->
<?php
/* middleware */
if(!isset($_REQUEST['username'])){
  Header('Location: signin.php');
}
?>
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
      <h1 class="searchH1">Buscando partida</h1>
      <div class="buttons">
        <a href="game.local.php?username=<?php echo $_REQUEST['username'] ?>">jugar vs IA</a>
        <a href="game.create.php?username=<?php echo $_REQUEST['username'] ?>">crear partida</a>
      </div>
    </header>
    <section class="games">
      <?php
         $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
         $sql = "SELECT * FROM partides WHERE ISNULL(guest)";
         $result=mysqli_query($con, $sql) or exit(mysqli_error($con));

         $x = true;
          while($reg = mysqli_fetch_array($result)){
            $x = false;
            ?>
            <div>
              <span>
                <h3>Partida <?php echo $reg['id_partida'] ?></h3>
                <h3>Creada por: <?php echo $reg['host']?></h3><h3>-</h3>
                <h3><?php echo $reg['data'] ?></h3>
              </span>
              <a href="game.join.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $reg['id_partida'] ?>">jugar</a>
            </div>
            <?php
          }
          if($x){
            echo '<h1>NO HAY NINGUNA PARTIDA DISPONIBLE :(</h1>';
          };
      ?>
    </section>
    <script>
      setTimeout(() => {
        window.location.reload();
      }, 3000);
    </script>
  </main>
</body>
</html>