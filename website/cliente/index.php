<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title>Back Office - EVS de Sucesso</title>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="js/plugins/morris/morris.css">
  <link rel="stylesheet" href="js/plugins/icheck/skins/minimal/blue.css">
  <link rel="stylesheet" href="js/plugins/select2/select2.css">
  <link rel="stylesheet" href="js/plugins/fullcalendar/fullcalendar.css">
  <link rel="stylesheet" href="js/plugins/datepicker/datepicker.css">
  <link rel="stylesheet" href="js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css">
  <link rel="stylesheet" href="js/plugins/magnific/magnific-popup.css">

  <!-- App CSS -->
  <link rel="stylesheet" href="css/target-admin.css">
  <link rel="stylesheet" href="css/custom.css">


  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body>

  <div class="navbar">

  <div class="container">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-cogs"></i>
      </button>

      <a class="navbar-brand navbar-brand-image" href="index.php">
        <img src="img/logo.png" alt="Site Logo" class="logo-top-bar">
      </a>

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">

      

      <ul class="nav navbar-nav noticebar navbar-left">

        <li class="dropdown">
          <a href="index.php" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
            <span class="navbar-visible-collapsed">&nbsp;Notifications&nbsp;</span>
            <span class="badge">3</span>
          </a>

          <ul class="dropdown-menu noticebar-menu" role="menu">
            <li class="nav-header">
              <div class="pull-left">
                Notifications
              </div>

              <div class="pull-right">
                <a href="javascript:;">Mark as Read</a>
              </div>
            </li>

            <li>
              <a href="page-notifications.html" class="noticebar-item">
                <span class="noticebar-item-image">
                  <i class="fa fa-cloud-upload text-success"></i>
                </span>
                <span class="noticebar-item-body">
                  <strong class="noticebar-item-title">Templates Synced</strong>
                  <span class="noticebar-item-text">20 Templates have been synced to the Mashon Demo instance.</span>
                  <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 12 minutes ago</span>
                </span>
              </a>
            </li>

            <li>
              <a href="page-notifications.html" class="noticebar-item">
                <span class="noticebar-item-image">
                  <i class="fa fa-ban text-danger"></i>
                </span>
                <span class="noticebar-item-body">
                  <strong class="noticebar-item-title">Sync Error</strong>
                  <span class="noticebar-item-text">5 Designs have been failed to be synced to the Mashon Demo instance.</span>
                  <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                </span>
              </a>
            </li>

            <li class="noticebar-menu-view-all">
              <a href="page-notifications.html">View All Notifications</a>
            </li>
          </ul>
        </li>

      </ul>

      <div class="aviso-top">
        <i class="fa fa-clock-o"></i>&nbsp; Seu plano expira em: 10/10/2015
      </div>

      <ul class="nav navbar-nav navbar-right">   

        <li>
          <a href="#!">Como usar</a>
        </li>

        <li class="dropdown navbar-profile">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#!">
            <img src="http://www.sistemaeds.com.br/data/uploads/imagens/usuario/foto_2014-12-15-15-35-52_548f1bf8936f3.jpg" class="navbar-profile-avatar" alt="">
            <i class="fa fa-caret-down"></i>
          </a>

          <ul class="dropdown-menu" role="menu">

            <li>
              <a href="?p=usuarios/perfil">
                <i class="fa fa-user"></i> 
                &nbsp;&nbsp;Meu Perfil
              </a>
            </li>

            <li>
              <a href="?p=configuracoes">
                <i class="fa fa-cogs"></i> 
                &nbsp;&nbsp;Configurações
              </a>
            </li>

            <li class="divider"></li>

            <li>
              <a href="#!">
                <i class="fa fa-sign-out"></i> 
                &nbsp;&nbsp;Sair
              </a>
            </li>

          </ul>

        </li>

      </ul>

       



       

    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->

  <div class="mainbar">

  <div class="container">

    <a href="index.php">
      <img src="img/logo-top.png" class="logo-top">
    </a>

    <button type="button" class="btn mainbar-toggle" data-toggle="collapse" data-target=".mainbar-collapse">
      <i class="fa fa-bars"></i>
    </button>

    <div class="mainbar-collapse collapse">

      <ul class="nav navbar-nav mainbar-nav">

        <li class="active">
          <a href="index.php">
            <i class="fa fa-dashboard"></i>
            Início
          </a>
        </li>

        <li>
          <a href="#!">
            <i class="fa fa-th-large"></i>
              Admin. Estoque<br><span style="color:#e74c3c; text-shadow: none;">[EM BREVE]</span>
          </a>
        </li>

        <li class="dropdown ">
          <a href="#!" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-bar-chart-o"></i>
            Relatórios
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">
            <li><a href="?p=relatorios/detalhado-de-vendas" class="orange"><i class="fa fa-list-alt nav-icon"></i> Detalhado de Vendas</a></li>
            <li><a href="?p=relatorios/diario-detalhado-de-atividade" class="gray"><i class="fa fa-list-alt nav-icon"></i> Diário Detalhado de Atividade</a></li>
            <li><a href="?p=relatorios/mensal-de-ganhos" class="green"><i class="fa fa-list-alt nav-icon"></i> Mensal de Ganhos</a></li>
            <li><a href="?p=relatorios/resumo-de-atividade-diaria" class="red"><i class="fa fa-list-alt nav-icon"></i> Resumo de Atividade Diária</a></li>
            <li><a href="?p=relatorios/semanal-temporario" class="blue"><i class="fa fa-list-alt nav-icon"></i> Semanal Temporário</a></li>
            <li><a href="?p=relatorios/ganhos-totais" class="yellow"><i class="fa fa-list-alt nav-icon"></i> Ganhos Totais</a></li>
          </ul>
        </li>

        <li>
          <a href="?p=usuarios/clientes">
          <i class="fa fa-users"></i> 
          Clientes
          </a>
        </li>

        <li>
          <a href="?p=usuarios/anfitrioes">
          <i class="fa fa-user-md"></i> 
          Anfitriões
          </a>
        </li>

        <li>
          <a href="?p=configuracoes">
          <i class="fa fa-cog"></i> 
          Configurações
          </a>

          <!--ul class="dropdown-menu" role="menu">
            <li>
              <a href="page-notifications.html">
              <i class="fa fa-bell"></i> 
              &nbsp;&nbsp;Notifications
              </a>
            </li>     

            <li>
              <a href="ui-icons.html">
              <i class="fa fa-smile-o"></i> 
              &nbsp;&nbsp;Font Icons
              </a>
            </li>

            <li class="dropdown-submenu">
              <a tabindex="-1" href="#">
              <i class="fa fa-ban"></i> 
              &nbsp;&nbsp;Error Pages
              </a>

              <ul class="dropdown-menu">
                <li>
                  <a href="page-404.html">
                  <i class="fa fa-ban"></i> 
                  &nbsp;&nbsp;404 Error
                  </a>
                </li>

                <li>
                  <a href="page-500.html">
                  <i class="fa fa-ban"></i> 
                  &nbsp;&nbsp;500 Error
                  </a>
                </li>
              </ul>
            </li>

            <li class="dropdown-submenu">

              <a tabindex="-1" href="#">
              <i class="fa fa-lock"></i> 
              &nbsp;&nbsp;Login Pages
              </a>

              <ul class="dropdown-menu">
                <li>
                  <a href="account-login.html">
                  <i class="fa fa-unlock"></i> 
                  &nbsp;&nbsp;Login
                  </a>
                </li>

                <li>
                  <a href="account-login-social.html">
                  <i class="fa fa-unlock"></i> 
                  &nbsp;&nbsp;Login Social
                  </a>
                </li>

                <li>
                  <a href="account-signup.html">
                  <i class="fa fa-star"></i> 
                  &nbsp;&nbsp;Signup
                  </a>
                  </li>

                <li>
                  <a href="account-forgot.html">
                  <i class="fa fa-envelope"></i> 
                  &nbsp;&nbsp;Forgot Password
                  </a>
                </li>
              </ul>
            </li> 

            <li class="divider"></li>

            <li>
              <a href="page-blank.html">
              <i class="fa fa-square-o"></i> 
              &nbsp;&nbsp;Blank Page
              </a>
            </li> 

          </ul>
        </li>

      </ul-->

    </div> <!-- /.navbar-collapse -->   

  </div> <!-- /.container --> 

</div> <!-- /.mainbar -->


<div class="container">

  <div class="content">

    <div class="content-container">

      <?php
        if( isset($_GET['p']) ){
          include $_GET['p'] .'.php';
        }else{
          include 'inicio.php';
        }
      ?>

    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->

<footer class="footer">

  <div class="container">

    <div class="row">

      <div class="col-sm-3">

        <h4>Sobre o EVS de Sucesso</h4>
        <br>
        <p>Gerencie seu espaço de sucesso.</p>  
        <hr>    
        <p>&copy; 2015 EVS de Sucesso</p>

      </div> <!-- /.col -->

      <div class="col-sm-3"> 

        <h4>Menu</h4>

        <br>

        <ul class="icons-list">
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">Admin. Estoque</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">Relatórios</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=usuarios/clientes">Clientes</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=usuarios/anfitrioes">Anfitriões</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=configuracoes">Configurações</a>
          </li>
        </ul>          

      </div> <!-- /.col -->

      <div class="col-sm-3">

        <h4>FAQ</h4>
        <br>
        <ul class="icons-list">
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=videos">Vídeos - tutoriais</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">Eu posso utilizar o aplicativo em computador?</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">Posso testar o aplicativo antes de comprar?</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">É difícil usar o aplicativo?</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="javascript:;">Para utilizar o aplicativo eu preciso estar conectado a Internet?</a>
          </li>
        </ul>          

      </div> <!-- /.col -->

      <div class="col-sm-3">

        <h4>Relatórios</h4>

        <br>

        <ul class="icons-list">
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=relatorios/detalhado-de-vendas">Relatório Detalhado de Vendas</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=relatorios/diario-detalhado-de-atividade">Relatório Diário Detallhado de Atividade</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=relatorios/mensal-de-ganhos">Relatório Mensal de Ganhos</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=relatorios/resumo-de-atividade-diaria">Resumo de Atividade Diária</a>
          </li>
          <li>
            <i class="fa fa-angle-double-right icon-li"></i>
            <a href="?p=relatorios/semanal-temporario">Relatório Semanal Temporário</a>
          </li>
        </ul>        

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->
  
</footer>

  <script src="js/libs/jquery-1.10.1.min.js"></script>
  <script src="js/libs/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="js/libs/bootstrap.min.js"></script>

  <!--[if lt IE 9]>
  <script src="./js/libs/excanvas.compiled.js"></script>
  <![endif]-->
  
  <!-- Plugin JS -->
  <script src="js/plugins/icheck/jquery.icheck.js"></script>
  <script src="js/plugins/select2/select2.js"></script>
  <script src="js/libs/raphael-2.1.2.min.js"></script>
  <script src="js/plugins/morris/morris.min.js"></script>
  <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>
  <script src="js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>
  <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="./js/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="./js/plugins/datatables/DT_bootstrap.js"></script>
  <script src="./js/plugins/tableCheckable/jquery.tableCheckable.js"></script>
  <script src="./js/plugins/icheck/jquery.icheck.min.js"></script>
  <script src="js/plugins/magnific/jquery.magnific-popup.min.js"></script>

  <!-- App JS -->
  <script src="js/target-admin.js"></script>
  
  <!-- Plugin JS -->
  <script src="js/demos/dashboard.js"></script>
  <script src="js/demos/calendar.js"></script>
  <script src="js/demos/charts/morris/area.js"></script>
  <script src="js/demos/charts/morris/donut.js"></script>
  <script src="js/demos/form-extended.js"></script>
  


  
</body>
</html>
