<!-- PARTIDA -->
<?php
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  };
  if(!isset($_REQUEST['id'])){
    Header('Location: game.search.php');
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
  <?php
    $id = $_REQUEST['id'];
    $username = $_REQUEST['username'];
    $state = $_REQUEST['state'];

    $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
    $sql = "SELECT * FROM partides WHERE id_partida=$id";
    $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
    $reg = mysqli_fetch_array($result);
  
    
    if(isset($_REQUEST['game'])){
      /* Empieza la partida */
      if(isset($_REQUEST['word'])){
        /* Guarda la palabra */
        saveWord($id,$username,$state, $_REQUEST['word']);
      }else{
        /* Introducir palabra */
        setWord($id,$username,$state);
      }
    }else{
      if($reg['hostWord'] == null || $reg['guestWord'] == null){
        /* Esperando palabra de los jugadores */
        waitingWord($id);
      }else{
        if(isset($_REQUEST['letter'])){
          setLetter($id, $_REQUEST['letter'], $state);
        }
        checkWinner($id);
        if($reg['turn'] == $state){
          play($id);
        }else{
          waiting($id);
        };
      }
    }

    /* Turno del jugador */
    function play($id){
      ?>
      <main>
        <header>
          <h1>Partida <?php echo $id ?> - Tú turno</h1>
          <div class="buttons">
            <a style="display: none;"></a>
            <a style="display: none;"></a>
          </div>
        </header>
  
      </main>
      <?php
    }

    /* Jugador en espera */
    function waiting($id){
      ?>
      <main>
        <header>
          <h1 class="searchH1">Partida <?php echo $id ?> - Espernado movimiento del rival</h1>
          <div class="buttons">
            <a style="display: none;"></a>
            <a style="display: none;"></a>
          </div>
        </header>
      </main>
      <script>
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      </script>
    <?php
    };
    
    /* Esperando palabra del rival */
    function waitingWord($id){
      ?>
      <main>
        <header>
          <h1 class="searchH1">Partida <?php echo $id ?> - Espernado palabra del rival</h1>
          <div class="buttons">
            <a style="display: none;"></a>
            <a style="display: none;"></a>
          </div>
        </header>
      </main>
      <script>
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      </script>
    <?php
    };

    /* Selecinar una palabra */
    function setWord($id,$username,$state){
      ?>
      <main>
        <header>
          <h1>Partida <?php echo $id ?> - Escribe una palabra</h1>
          <div class="buttons">
            <a style="display: none;"></a>
            <a style="display: none;"></a>
          </div>
        </header>
        <section class="word">
          <form action="game.play.php">
            <div class="text-field">
                <input id="titulo" required autocomplete="off" name="word" onkeypress="return event.charCode != 32" maxlength="10" minlength="4">
                <label class="label">Palabra (min: 4 letras, max: 10 letras)</label>
            </div>
            <input hidden type="text" name="username" value="<?php echo $_REQUEST['username']; ?>">
            <input hidden type="text" name="id" value="<?php echo $_REQUEST['id']; ?>"> 
            <input hidden type="text" name="state" value="<?php echo $_REQUEST['state']; ?>"> 
            <input hidden type="text" name="game" value="start"> 
            <div class="btn-box word-btn-box">
              <input type="submit" value="Buscar partida">
            </div>
          </form>
        </section>
      </main>
      <?php
    }

    /* Añade palabra */
    function saveWord($id,$username,$state, $word){
      $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
      if($state == 'guest'){
        $sql = "UPDATE partides SET hostWord='$word' WHERE id_partida=$id";
      }else{
        $sql = "UPDATE partides SET guestWord='$word' WHERE id_partida=$id";
      }
      $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
      Header('Location: game.play.php?username='.$username.'&id='.$id.'&state='.$state);
    }
    
    /* Comprueba/añade letra + pasa de turno */
    function setLetter($id, $letter, $player){
      return 0;
    };

    /* Mira si hay un ganador */
    function checkWinner($id){
      return 0;
    };
  ?>
</body>
</html>