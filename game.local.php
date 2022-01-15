<!-- Partida vs IA -->
<?php
  /* middlewares */
  if(!isset($_REQUEST['username'])){
    Header('Location: signin.php');
  };

  /* Crear partida */
  if(!isset($_REQUEST['id'])){
    $words = ["cajero", "zorro", "kilogramo","viento", "diente", "cabello", "fuego", "lluvia","cosas","palmera","levantar","elefante","segar","socorro","nido","masa","gastar","lanzar","cuatro","cortina","rotar","emparejar","alto","vestuario","criticar","ostra","estatua","casco","vertical","norte","nido","rotar"];
    $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
    $sql = "INSERT INTO partides VALUES (null,'".date("Y-m-d")."','".$_REQUEST['username']."','IA',NULL,'".$words[random_int(0,30)]."',1,1,'host',NULL)";
    $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
    $id=mysqli_insert_id($con);
    Header('Location: game.local.php?username='.$_REQUEST['username'].'&id='.$id.'&state=host&game=true');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahorcado vs IA</title>
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
    if(isset($_REQUEST['word'])){
      /* Guarda la palabra */
      saveWord($id,$username,'host', $_REQUEST['word']);
    }else{
      /* Introducir palabra */
      setWord($id,$username,'host');
    };
  }else{
    if(isset($_REQUEST['letter'])){
      /* Guarda letra + cambia de turno */
      setLetter($id,$username,'host', $_REQUEST['letter'],$reg);
    };
     /* Mira si hay un ganador */
     if(checkWinner($id,$reg) === 0){
      if($reg['turn'] == 'host'){
        /* Turno del jugador */
        play($id, $reg);
      }else{
        /* Jugador en espera */
        brain($id,$username,$reg);
      };
    }else{
     
      /* Hay un ganador */
      winnerScreen($id,$reg);
    };
  }

  /* IA */
  function brain($id,$username,$reg){
    $letters = 'qwertyuioplkjhgfdsazxcvbnm';
    $letter = $letters[random_int(0,26)];
    if(strpos($reg['guestLetters'],$letter)){
      brain($id,$username,$reg);
    }else{
      setLetter($id,$username,'guest',$letter,$reg);
    };
  };

  /* Turno del jugador */
  function play($id,$reg){
    ?>
    <main>
      <header>
        <h1>Partida <?php echo $id ?> - Tú turno</h1>
        <div class="buttons">
          <a style="display: none;"></a>
          <a style="display: none;"></a>
        </div>
      </header>
      <section class="game">
        <div class="board">
            <div class="img">
            <img src='./img/<?php echo getLives('host',$reg); ?>.png'>
          </div>
          <div class="foundedLetters">
            <?php getLetters('host',$reg); ?>
          </div>
          <div class="keyboard">
          <?php keyboard('host',$reg) ?>
          </div>
        </div>
        <div class="info">
          <div class="hostLives">
            <h1>TUS VIDAS</h1>
            <div class="lives">
              <?php printLives('host', $reg) ?> 
            </div>
          </div>
          <div class="guestGame">
            <h1>RIVAL</h1>
            <div class="guestBoard">
              <img src='./img/<?php echo getLives('guest',$reg); ?>.png'>
            </div>
            <div class="guestLetters">
              <?php getLetters('guest',$reg) ?>
            </div>
            <div class="guestLives">
            <?php printLives('guest', $reg) ?> 

            </div>
          </div>
        </div>
      </section>
    </main>
    <?php
  };

  /* Final Pantalla ganador */
  function  winnerScreen($id,$reg){
    ?>
    <main>
      <header>
        <h1 >Partida <?php echo $id ?> - <?php
          if(checkWinner($id,$reg) == 'host'){
            echo "Has ganado la partida!";
          }else{
            echo "Has perdido";
          }
        ?></h1>
        <div class="buttons">
          <a style="display: none;"></a>
          <a style="display: none;"></a>
          <a href="game.search.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>">salir</a>
        </div>
      </header>
    </main>
    <section class="game">
        <div class="board">
            <div class="img">
            <img src='./img/<?php echo getLives('host',$reg); ?>.png'>
          </div>
          <div class="foundedLetters">
            <?php getLetters('host',$reg); ?>
          </div>
          <div class="keyboard">
          <?php dissabledkeyboard('host',$reg) ?>
          </div>
        </div>
        <div class="info">
          <div class="hostLives">
            <h1>TUS VIDAS</h1>
            <div class="lives">
              <?php printLives('host', $reg) ?> 
            </div>
          </div>
          <div class="guestGame">
            <h1>RIVAL</h1>
            <div class="guestBoard">
              <img src='./img/<?php echo getLives('guest',$reg); ?>.png'>
            </div>
            <div class="guestLetters">
              <?php getLetters('guest',$reg) ?>
            </div>
            <div class="guestLives">
            <?php printLives('guest', $reg) ?> 

            </div>
          </div>
        </div>
      </section>
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
        <form action="game.local.php">
          <div class="text-field">
              <input id="titulo" required autocomplete="off" name="word" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32) && (event.charCode != 32)" maxlength="10" minlength="4">
              <label class="label">Palabra (min: 4 letras, max: 10 letras)</label>
          </div>
          <input hidden type="text" name="username" value="<?php echo $_REQUEST['username']; ?>">
          <input hidden type="text" name="id" value="<?php echo $_REQUEST['id']; ?>"> 
          <input hidden type="text" name="state" value="<?php echo $_REQUEST['state']; ?>"> 
          <input hidden type="text" name="game" value="start"> 
          <div class="btn-box word-btn-box">
            <input type="submit" value="jugar">
          </div>
        </form>
      </section>
    </main>
    <?php
  };

  /* Guarda palabra */
  function saveWord($id,$username,$state, $word){
    $word = strtolower($word);
    $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
    if($state == 'host'){
      $sql = "UPDATE partides SET hostWord='$word' WHERE id_partida=$id";
    }else{
      $sql = "UPDATE partides SET guestWord='$word' WHERE id_partida=$id";
    }
    $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
    Header('Location: game.local.php?username='.$username.'&id='.$id.'&state='.$state);
  };
  
  /* Comprueba/añade letra + pasa de turno */
  function setLetter($id,$username,$state, $letter, $reg){
    $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
    if($state == 'host'){
      $letters = $reg['hostLetters'].$letter;
      $sql = "UPDATE partides SET hostLetters='$letters', turn='guest' WHERE id_partida=$id";
    }else{
      $letters = $reg['guestLetters'].$letter;
      $sql = "UPDATE partides SET guestLetters='$letters', turn='host' WHERE id_partida=$id";
    }
    echo $sql;
    $result=mysqli_query($con, $sql) or exit(mysqli_error($con));
    Header('Location: game.local.php?username='.$username.'&id='.$id.'&state='.$state);
  };

  /* Mira si hay un ganador */
  function checkWinner($id,$reg){
    $livesHost = 7-getLives('host',$reg);
    $livesGuest = 7-getLives('guest',$reg);
    $con = mysqli_connect("localhost","daw_user","P@ssw0rd","ahorcado") or exit(mysqli_connect_error());
    
    /* control vidas */
    if($livesHost == 0 or $livesGuest == 0){
      
      if($livesHost == 0){
        return 'guest';
      }else{
        return 'host';
      }
    }

    /* control palabra */
    /* host */
    $x = 0;
    for($i = 0; $i < strlen($reg['guestWord']); $i++){
      if(strpos($reg['hostLetters'] ,$reg['guestWord'][$i])){
        $x++;
      }
    }
    if($x == strlen($reg['guestWord'])){
      return 'host';
    };
    /* guest */
    $x = 0;
    for($i = 0; $i < strlen($reg['hostWord']); $i++){
      if(strpos($reg['guestLetters'] ,$reg['hostWord'][$i])){
        $x++;
      }
    }
    if($x == strlen($reg['hostWord'])){
      return 'guest';
    };
    
    return 0;
  };
  
  /* Devuelve número de vidas */
  function getLives($state,$reg){
    $x = 0;
    if($state == 'host'){
      $word = removeDuplicateChar($reg['guestWord']);
      for($i = 0; $i < strlen($word); $i++){
        if(strpos($reg['hostLetters'] ,$word[$i])){
          $x++;
        }
      }
      $y = strlen($reg['hostLetters'])-1;
      if(strpos($reg['hostLetters'],'ñ')){
        $y--;
      }
      return $y-$x;
    }else{
      $word = removeDuplicateChar($reg['hostWord']);
      for($i = 0; $i < strlen($word); $i++){
        if(strpos($reg['guestLetters'] ,$word[$i])){
          $x++;
        }
      }
      $y = strlen($reg['guestLetters'])-1;
      if(strpos($reg['guestLetters'],'ñ')){
        $y--;
      }
      return ($y-$x) ;
    }
  };

  /* Imprime letras encontradas  */
  function getLetters($state,$reg){
    if($state == 'host'){
      for($i = 0; $i < strlen($reg['guestWord']); $i++){
        if(strpos($reg['hostLetters'] ,$reg['guestWord'][$i])){
          echo "<span>".$reg['guestWord'][$i]."</span>";
        }else{
          echo "<span class='bar'></span>";
        }
      }
    }else{
      for($i = 0; $i < strlen($reg['hostWord']); $i++){
        if(strpos($reg['guestLetters'] ,$reg['hostWord'][$i])){
          echo "<span>".$reg['hostWord'][$i]."</span>";
        }else{
          echo "<span class='bar'></span>";
        }
      }
    }
  };
  
  /* Imprime botones del teclado */
  function keyboard($state,$reg){
      if($state == 'host'){
        $state = 'hostLetters';
      }else{
        $state = 'guestLetters';
      };

    ?>
    <!-- Q-P -->
    <div>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=q'" <?php if(strpos($reg[$state],'q')){echo 'class="disabled"';}; ?>>Q</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=w'" <?php if(strpos($reg[$state],'w')){echo 'class="disabled"';};; ?>>W</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=e'" <?php if(strpos($reg[$state],'e')){echo 'class="disabled"';};; ?>>E</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=r'" <?php if(strpos($reg[$state],'r')){echo 'class="disabled"';};; ?>>R</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=t'" <?php if(strpos($reg[$state],'t')){echo 'class="disabled"';};; ?>>T</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=y'" <?php if(strpos($reg[$state],'y')){echo 'class="disabled"';};; ?>>Y</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=u'" <?php if(strpos($reg[$state],'u')){echo 'class="disabled"';};; ?>>U</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=i'" <?php if(strpos($reg[$state],'i')){echo 'class="disabled"';};; ?>>I</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=o'" <?php if(strpos($reg[$state],'o')){echo 'class="disabled"';};; ?>>O</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=p'" <?php if(strpos($reg[$state],'p')){echo 'class="disabled"';};; ?>>P</button>
    </div>
    <!-- A-Ñ -->
    <div>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=a'" <?php if(strpos($reg[$state],'a')){echo 'class="disabled"';}; ?>>A</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=s'" <?php if(strpos($reg[$state],'s')){echo 'class="disabled"';};; ?>>S</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=d'" <?php if(strpos($reg[$state],'d')){echo 'class="disabled"';};; ?>>D</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=f'" <?php if(strpos($reg[$state],'f')){echo 'class="disabled"';};; ?>>F</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=g'" <?php if(strpos($reg[$state],'g')){echo 'class="disabled"';};; ?>>G</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=h'" <?php if(strpos($reg[$state],'h')){echo 'class="disabled"';};; ?>>H</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=j'" <?php if(strpos($reg[$state],'j')){echo 'class="disabled"';};; ?>>J</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=k'" <?php if(strpos($reg[$state],'k')){echo 'class="disabled"';};; ?>>K</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=l'" <?php if(strpos($reg[$state],'l')){echo 'class="disabled"';};; ?>>L</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=ñ'" <?php if(strpos($reg[$state],'ñ')){echo 'class="disabled"';};; ?>>Ñ</button>
    </div>
    <!-- Z-M -->
    <div>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=z'" <?php if(strpos($reg[$state],'z')){echo 'class="disabled"';};; ?>>Z</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=x'" <?php if(strpos($reg[$state],'x')){echo 'class="disabled"';};; ?>>X</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=c'" <?php if(strpos($reg[$state],'c')){echo 'class="disabled"';};; ?>>C</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=v'" <?php if(strpos($reg[$state],'v')){echo 'class="disabled"';};; ?>>V</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=b'" <?php if(strpos($reg[$state],'b')){echo 'class="disabled"';};; ?>>B</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=n'" <?php if(strpos($reg[$state],'n')){echo 'class="disabled"';};; ?>>N</button>
        <button onclick="window.location.href='game.local.php?username=<?php echo $_REQUEST['username']?>&id=<?php echo $_REQUEST['id']?>&state=host&letter=m'" <?php if(strpos($reg[$state],'m')){echo 'class="disabled"';};; ?>>M</button>
    </div>
    <?php
  };
  
  /* Imprime botones del tecldo desactivados */
  function dissabledkeyboard($state, $reg){
    $state = $state == 'host' ? 'hostLetters' :  'guestLetters';
    echo "<div><button class='disabled'>Q</button><button class='disabled'>W</button><button class='disabled'>E</button><button class='disabled'>R</button><button class='disabled'>T</button><button class='disabled'>Y</button><button class='disabled'>U</button><button class='disabled'>I</button><button class='disabled'>O</button><button class='disabled'>P</button></div><div><button class='disabled'>A</button><button class='disabled'>S</button><button class='disabled'>D</button><button class='disabled'>F</button><button class='disabled'>G</button><button class='disabled'>H</button><button class='disabled'>J</button><button class='disabled'>K</button><button class='disabled'>L</button><button class='disabled'>Ñ</button></div><div><button class='disabled'>Z</button><button class='disabled'>X</button><button class='disabled'>C</button><button class='disabled'>V</button><button class='disabled'>B</button><button class='disabled'>N</button><button class='disabled'>M</button></div>";
  };

  /* Imprime vidas */
  function printLives($state, $reg){
    $lives = 7-getLives($state,$reg);
    for($i = 0; $i < 7; $i++){
      if($lives != 0){
        echo "<img src='./img/h1.png'>";
        $lives--;
      }else{
        echo "<img src='./img/h2.png'>";
      }
    }
  };

  /* Elimina letras duplicadas */
  function removeDuplicateChar($word){
    $x = [];
    for($i = 0; $i < strlen($word);$i++){
      array_push($x,$word[$i]);
    }
    return implode(array_unique($x));
  };
  ?>
</body>
</html>