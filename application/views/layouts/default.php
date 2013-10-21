<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $template['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="<?php echo base_url('favicon.ico'); ?>" rel='icon' type='image/x-icon'> 
    <!-- bootstrap css -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/html5shiv.js'); ?>"></script>
    <link href="<?php echo base_url('assets/icomoon/style.css'); ?>" rel="stylesheet">
    <!--[if lte IE 7]>
    <script src="css/icomoon-font/lte-ie7.js">
    </script>
    <![endif]-->
	<link href="<?php echo base_url('assets/css/main.css'); ?>" rel="stylesheet"> <!-- Important. For Theming change primary-color variable in main.css  -->
	
	<?php echo $template['css']; ?>
	
	<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-migrate.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.ui.core.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.cookie.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.ba-dotimeout.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.scrollUp.js'); ?>"></script>
	
	<?php echo $template['js_header']; ?>
	
	<style type="text/css">
		header {
			height: 77px;
		}
	</style>
	
</head>
<body>
    <header>
		<a href="<?php echo base_url(); ?>" class="logo"><img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="logo" />
		</a>
		<?php
			if (isset($auth_user)) {
		?>
		<div class="btn-group">
			<button class="btn btn-primary"><?php echo $auth_user['username']; ?></button>
			<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
			<ul class="dropdown-menu pull-right">
				<li><a href="<?php echo site_url('auth/user/profile'); ?>">Edit Profile</a></li>
				<li><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
			</ul>
		</div>
		<ul class="mini-nav">
			<li>
				<!--a href="#" data-toggle="dropdown">
					<div class="fs1" aria-hidden="true" data-icon="&#xe070;"></div>
					<span class="info-label badge badge-success">9</span>
				</a-->
				<ul class="dropdown-menu pull-right">
					<li><a href="#">Edit Profile</a></li>
					<li><a href="#">Logout</a></li>
				</ul>
			</li>
		</ul>
		<?php
			}
		?>
		
	</header>
    <div class="container-fluid">
		<div class="dashboard-container">
			<?php
				if (isset($top_nav))
					$this->load->view($top_nav); 
			?>
			<?php
				if (isset($sub_nav))
					$this->load->view($sub_nav); 
			?>
			<div class="dashboard-wrapper">
				
				<?php echo $template['content']; ?>
				
			</div>
		</div>
		<!--/.fluid-container-->
    </div>
    <footer style="background: url(<?php echo base_url('assets/img/logo1.png'); ?>) no-repeat scroll 7px 7px #3693CF; padding-left: 115px;">
		<p>&copy; Venus I-Media</p>
    </footer>
    
	<?php echo $template['js_footer']; ?>
    
    <script type="text/javascript">
		//ScrollUp
		$(function () {
			$.scrollUp({
				scrollName: 'scrollUp', // Element ID
				topDistance: '300', // Distance from top before showing element (px)
				topSpeed: 300, // Speed back to top (ms)
				animation: 'fade', // Fade, slide, none
				animationInSpeed: 400, // Animation in speed (ms)
				animationOutSpeed: 400, // Animation out speed (ms)
				scrollText: 'Scroll to top', // Text for element
				activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
			});
		});
		
		//Tooltip
		$('a').tooltip('hide');

		//Popover
		$('.popover-pop').popover('hide');

		//Collapse
		$('#myCollapsible').collapse({
			toggle: false
		})
    </script>
</body>
</html>