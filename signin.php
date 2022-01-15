<!-- INICIAR SESIÓN -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahorcado</title>
  <link rel="stylesheet" href="./css/signin.css">
</head>
<body>
  <main class="home">
    <img src="./img/fondo.jpg" alt="">
    <section>
      <div class="logo">
        <h1>AHORCADO</h1>
        <h1>ONLINE</h1>
      </div>
      <form action="game.search.php">
        <div class="text-field">
            <input id="titulo" required autocomplete="off" name="username">
            <label class="label">Nombre de usuario</label>
        </div>
        <div class="btn-box">
          <input type="submit" value="Buscar partida">
        </div>
      </form>
    </section>
  </main>
</body>
</html>