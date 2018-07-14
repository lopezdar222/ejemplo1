<?php
	session_start();
	$_SESSION['ubicacion']="includes/";
	require $_SESSION['ubicacion']."config.php";
	if (isset($_POST['usuario']))
	{
		$qry_usr = "select u.id_usuario, u.usuario, u.clave, u.nombre, u.apellido, u.id_rol, u.email, u.foto, r.descripcion_rol, r.descripcion_rol_chica, ";
		$qry_usr = $qry_usr."DATE_FORMAT(u.fecha_creacion, '%d/%m/%Y') as fecha_creacion from usuario u join rol r on u.id_rol = r.id_rol ";
		$qry_usr = $qry_usr."where u.usuario = '".$_POST['usuario']."' and estado = 0";
		$rs_usr = $con->recordselect($qry_usr);
		$row_usr = mysql_fetch_array($rs_usr);
		if ($row_usr['clave'] == $_POST['password'] And $row_usr['usuario'] == $_POST['usuario'])
		{
			$_SESSION['id_usuario'] = $row_usr['id_usuario'];
			$_SESSION['usuario'] = $row_usr['usuario'];
		}
		else
		{
			if ($row_usr['usuario'] == $_POST['usuario'])
			{
				$_SESSION['id_usuario'] = -1;
			}
			else
			{
				$_SESSION['id_usuario'] = -2;
			}
			$_SESSION['usuario'] = $_POST['usuario'];
		}
	}
	if (!isset($_SESSION['id_usuario']))
	{
		header('Location: index.php');
	}
	else
	{
		if ($_SESSION['id_usuario'] < 0)
		{
			header('Location: index.php');
		}
	}
	$_SESSION['nombre_usuario'] = $row_usr['nombre']." ".$row_usr['apellido'];
	if($row_usr['foto'] != '') {$_SESSION['foto_usuario'] = '/imagenes/usuarios/'.$row_usr['foto'];}
	else {$_SESSION['foto_usuario'] = '/imagenes/usuarios/usuario_sin_foto.jpg';}
	$_SESSION['rol_usuario'] = $row_usr['id_rol'];
	$_SESSION['fecha_creacion_usuario'] = $row_usr['fecha_creacion'];
	$_SESSION['rol_descripcion'] = $row_usr['descripcion_rol'];
	$_SESSION['rol_descripcion_chica'] = $row_usr['descripcion_rol_chica'];
	$_SESSION['id_sesion'] = session_id();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<title>Admin Vete</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<!-- Select2 -->
		<script src="plugins/select2/select2.full.min.js"></script><!-- date-range-picker -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
		<!--script src="plugins/daterangepicker/daterangepicker.js"></script-->
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- jvectormap -->
		<!--link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css"-->
		<!-- DataTables -->
		<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">			
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="../../plugins/select2/select2.min.css">		
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css"/>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript">
		function menu(accion)
		{
			var menu = "";
			switch (accion)
			{
				case 1:
					menu = 'contenido/menuAbmProductos.php';
					break;
				case 2:
					menu = 'contenido/menuAbmServicios.php';
					break;
				case 3:
					menu = 'contenido/menuAbmProveedores.php';
					break;
				case 4:
					menu = 'contenido/menuAbmClientes.php';
					break;
				case 5:
					menu = 'contenido/menuAbmMascotas.php';
					break;
				case 6:
					menu = 'contenido/menuAbmUsuarios.php';
					break;
				case 7:
					menu = 'contenido/menuMiPerfil.php';
					break;
				case 8:
					menu = 'contenido/menuCompras.php';
					break;
				case 9:
					menu = 'contenido/menuVentas.php';
					break;
				case 10:
					menu = 'contenido/menuGastos.php';
					break;
				case 11:
					menu = 'contenido/menuCobranzas.php';
					break;
				case 12:
					menu = 'contenido/menuPagos.php';
					break;
				case 13:
					menu = 'contenido/menuHistoriaClinica.php';
					break;
				case 14:
					menu = 'contenido/menuVacio.php';
					break;
				case 15:
					menu = 'contenido/menuReportes.php';
					break;
			}
			var parametros = {"accion" : accion};
			$.ajax(
			{
				data:  parametros,
				url:   menu,
				type:  'post',
				beforeSend: function () 
				{
					$("#contenido").html("Obteniendo, espere por favor...");
				},
				success:  function (response) 
				{
					$("#contenido").html(response);
				}
			});
		}
		function MostrarAlerta(accion)
		{
			var path = "contenido/alertasAjax.php";
			var alerta = "";
			if (accion == 1) {alerta = "#alerta_gastos";}
			if (accion == 2) {alerta = "#alerta_compras";}
			if (accion == 3) {alerta = "#alerta_ventas";}
			if (accion == 4) {alerta = "#alerta_stock";}
			if (accion == 5) {alerta = "#alerta_turno";}
			if (accion == 6) {alerta = "#alerta_turno";}
			var parametros = {"accion" : accion};
			$.ajax(
			{
				data:  parametros,
				url:   path,
				type:  'post',
				beforeSend: function () 
				{
					$(alerta).html("Calculando, espere por favor...");
				},
				success:  function (response) 
				{
					$(alerta).html(response);
				}
			});
			return false;
		}		
		</script>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition skin-yellow sidebar-mini">
		<div class="wrapper">
			<header class="main-header">
			<!-- Logo -->
				<a href="#" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><b>
						<?php echo $_SESSION['rol_descripcion_chica'];?>
					</b></span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b>
						<?php echo $_SESSION['rol_descripcion'];?>
					</b></span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!-- Navbar Right Menu -->
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
						<!-- Notifications: style can be found in dropdown.less -->
							<!--li class="dropdown notifications-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-bell-o"></i>
									<span class="label label-warning">5</span>
								</a>
								<ul class="dropdown-menu">
								<li class="header">Tenes 5 notificaciones</li>
									<li>
										<ul class="menu">
											<li>
												<a href="#">
													<i class="fa fa-users text-aqua"></i> Noti 1
												</a>
											</li>
											<li>
												<a href="#">
													<i class="fa fa-warning text-yellow"></i> Noti 2
												</a>
											</li>
											<li>
												<a href="#">
													<i class="fa fa-users text-red"></i> Noti 3
												</a>
											</li>
											<li>
												<a href="#">
													<i class="fa fa-shopping-cart text-green"></i> Noti 4
												</a>
											</li>
											<li>
												<a href="#">
													<i class="fa fa-user text-red"></i> Noti 5
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li-->
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo $_SESSION['foto_usuario'];?>" width="160px" height="160px" class="user-image" alt="Foto Usuario">
							<span class="hidden-xs"><?php echo $_SESSION['nombre_usuario'];?></span>
							</a>
							<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
							<img src="<?php echo $_SESSION['foto_usuario'];?>" width="160px" height="160px" class="img-circle" alt="Foto Usuario">

							<p>
							<?php echo $_SESSION['nombre_usuario']." - ";
							if ($_SESSION['rol_usuario'] == 1) {echo "Administrador";}
							?>
							<small>Miembro desde <?php echo $_SESSION['fecha_creacion_usuario'];?></small>
							</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-right">
									<a href="index.php?logout=1" class="btn btn-default btn-flat">Terminar Sesion</a>
								</div>
							</li>
							</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
		<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
				<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<img src="<?php echo $_SESSION['foto_usuario'];?>" width="160px" height="160px" class="img-circle" alt="User Image">
						</div>
						<div class="pull-left info">
							<p><?php echo $_SESSION['nombre_usuario'];?></p>
							<a href="#"><i class="fa fa-circle text-success"></i>Online</a>
						</div>
					</div>
				<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<?php if ($_SESSION['rol_usuario'] == 1) { //Administrador ?>
							<li class="header">MENU PRINCIPAL</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-file"></i> <span>ABMs</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li class="active"><a href="#" onclick="menu(1);"><i class="fa fa-product-hunt"></i> 	Productos</a></li>
									<li class="active"><a href="#" onclick="menu(2);"><i class="fa fa-suitcase"></i> 		Servicios</a></li>
									<li class="active"><a href="#" onclick="menu(3);"><i class="fa fa-medkit"></i> 			Proveedores</a></li>
									<li class="active"><a href="#" onclick="menu(4);"><i class="fa fa-folder"></i> 			Clientes</a></li>
									<li class="active"><a href="#" onclick="menu(5);"><i class="fa fa-paw"></i> 			Mascotas</a></li>
									<li class="active"><a href="#" onclick="menu(6);"><i class="fa fa-user-plus"></i> 		Usuarios</a></li>
								</ul>
							</li>
							<li><a href="#" onclick="menu(7);"><i class="fa fa-user"></i> 			<span>Mi Perfil</span></a></li>
							<li><a href="#" onclick="menu(8);"><i class="fa fa-shopping-cart"></i> 	<span>Compras</span></a></li>
							<li><a href="#" onclick="menu(9);"><i class="fa fa-sellsy"></i> 		<span>Ventas</span></a></li>
							<li><a href="#" onclick="menu(10);"><i class="fa fa-area-chart"></i> 	<span>Gastos</span></a></li>
							<li><a href="#" onclick="menu(11);"><i class="fa fa-dollar"></i> 		<span>Cobranzas</span></a></li>
							<li><a href="#" onclick="menu(12);"><i class="fa fa-dollar"></i> 		<span>Pagos</span></a></li>
							<li><a href="#" onclick="menu(13);"><i class="fa fa-book"></i> 			<span>Historia Clínica</span></a></li>
							<li><a href="#" onclick="menu(15);"><i class="fa fa-eye"></i> 			<span>Reportes</span></a></li>

						<?php }
						if ($_SESSION['rol_usuario'] == 2) { //Profesional ?>
							<li class="header">MENU PRINCIPAL</li>
							<li><a href="#" onclick="menu(7);"><i class="fa fa-user"></i> 			<span>Mi Perfil</span></a></li>
							<li><a href="#" onclick="menu(13);"><i class="fa fa-book"></i> 			<span>Historia Clínica</span></a></li>
						<?php }
						if ($_SESSION['rol_usuario'] == 3) { //Operador ?>
							<li class="header">MENU PRINCIPAL</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-file"></i> <span>ABMs</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li class="active"><a href="#" onclick="menu(4);"><i class="fa fa-folder"></i> 			Clientes</a></li>
									<li class="active"><a href="#" onclick="menu(5);"><i class="fa fa-paw"></i> 			Mascotas</a></li>
								</ul>
							</li>
							<li><a href="#" onclick="menu(7);"><i class="fa fa-user"></i> 			<span>Mi Perfil</span></a></li>
							<li><a href="#" onclick="menu(9);"><i class="fa fa-sellsy"></i> 		<span>Ventas</span></a></li>
							<li><a href="#" onclick="menu(11);"><i class="fa fa-dollar"></i> 		<span>Cobranzas</span></a></li>
							<li><a href="#" onclick="menu(13);"><i class="fa fa-book"></i> 			<span>Historia Clínica</span></a></li>
						<?php }?>					
						<!--li><a href="#" onclick="menu(14);"><i class="fa fa-bell"></i> 			<span>Alertas</span></a></li-->
						<!--li><a href="#" onclick="menu(15);"><i class="fa fa-tachometer"></i> 	<span>Estadisticas</span></a></li-->
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>

		<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>Administración Veterinaria</h1>
					<div id="alerta_turno" align="right"></div>
				</section>
			<!-- Main content -->
				<section class="content">
				<!-- Info boxes -->
					<?php if ($_SESSION['rol_usuario'] == 1) {?>			
					<div class="row">
					<!-- /.col -->
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div id="alerta_gastos"></div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div id="alerta_compras"></div>
						</div>
						<!-- fix for small devices only -->
						<div class="clearfix visible-sm-block"></div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div id="alerta_ventas"></div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div id="alerta_stock"></div>
						</div>
					<!-- /.col -->
					</div>
					<script type="text/javascript">
						MostrarAlerta(1);
						MostrarAlerta(2);
						MostrarAlerta(3);
						MostrarAlerta(4);
						MostrarAlerta(5);
					</script>
					<?php }?>
					<!-- /.row -->
					<div  class="row">
						<div class="col-md-12">
							<div class="box">
								<div id="contenido" align="center"></div>		
							</div>
						</div>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 1.0
				</div>
			</footer>
		</div>
		<!-- ./wrapper -->
		<!-- Bootstrap 3.3.6 -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
		<!-- DataTables -->
		<script src="plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>		
		<!-- SlimScroll -->
		<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="plugins/fastclick/fastclick.js"></script>
		<!-- bootstrap datepicker -->
		<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
		<!-- Select2 -->
		<script src="plugins/select2/select2.full.min.js"></script>
		<!-- bootstrap time picker -->
		<!--script src="plugins/timepicker/bootstrap-timepicker.min.js"></script-->			
	</body>
	<script>
		$(document).keypress(function (e) {
			if (e.which == 37) 
			{
				abmGuardar();
			}
		});
		setInterval(function(){ 
			MostrarAlerta(1);
			MostrarAlerta(2);
			MostrarAlerta(3);
			MostrarAlerta(4);
			MostrarAlerta(5); }, <?php echo $actualiza_Alertas;?>);
	</script>	
</html>
