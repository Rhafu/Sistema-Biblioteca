<!-- <style>
    .dropbtn {
    background-color: #0367A6;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
  }
  
  /* The container <div> - needed to position the dropdown content */
  .dropdown {
    position: relative;
    display: inline-block;
  }
  
  /* Dropdown Content (Hidden by Default) */
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }
  
  /* Links inside the dropdown */
  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }
  
  /* Change color of dropdown links on hover */
  .dropdown-content a:hover {background-color: #f1f1f1}
  
  /* Show the dropdown menu on hover */
  .dropdown:hover .dropdown-content {
    display: block;
  }
  
  /* Change the background color of the dropdown button when the dropdown content is shown */
  .dropdown:hover .dropbtn {
    background-color: #0367A6;
  }
</style> -->


<div class="login-usuario">
  <img src="./storage/usersImages/<?=$info->foto?>" alt="login-user" onClick="location.href='pagina-perfil.php'">
  <div class="dropdown-perfil">
      <?=$info->nome?>
      <div class="content-perfil">
          <a href="./meu-perfil.php" class="flip">Meu Perfil</a>
          <a href="./view-alunos.php" class="flip">Alunos</a>
          <a href="./view-livros.php" class="flip">Livros</a>
          <a href="./view-bibliotecarios.php" class="flip">Bibliotecarios</a>
          <a href="./view-locacoes-adm.php?status=ativo" class="flip">Locações</a>
          <a href="./logout.php" class="flip">Deslogar</a>
      </div>
  </div>
</div>