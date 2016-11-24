<?php
/**
 * Peak welcome controller. This is the default controller for Peak/Application/genericapp.ini
 * Its just a welcome (hello words) page... :P
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Controller_Internal_Pkwelcome extends Peak_Controller_Action
{

	/**
	 * Setup controller
	 */
    public function preAction()
    {
		if(APPLICATION_ENV !== 'development') exit();
		
        $this->view->engine('VirtualLayouts');
        $this->view->cache()->disable();
		
		error_reporting(false);
    }
    
	/**
	 * Intro action
	 */
    public function _index()
    {
		$this->view->after_intro = '';
		
		if(APPLICATION_CONFIG === 'genericapp.ini') {
			$this->view->intro_warn = 'but without it\'s own settings file...';
		}
		else {
			$filepath = APPLICATION_ABSPATH.'/'.APPLICATION_CONFIG;
			$filetype = pathinfo($filepath, PATHINFO_EXTENSION);
			
			if(!file_exists($filepath)) {
				$this->view->intro_warn = 'but your configuration is missing...';
				$this->view->application_folder_missing = '<br /><small style="color:red">Can\'t find this path :(</small>';
			}
			elseif(!in_array($filetype, array('php','ini'))) {
				$this->view->intro_warn = 'but your configuration file type is not supported...';
			}
			elseif(trim(file_get_contents($filepath)) === '') {
				$this->view->intro_warn = 'but your configuration file seems to be empty...';
			}
			else {
				if($filetype === 'ini') {
					//test ini to see if it, we will use genericapp.ini in dev mode only
					try { $conf = new Peak_Config_Ini($filepath, true);	}
					catch(Exception $e) {
						$this->view->intro_warn = 'but something gone wrong in your configuration... O_o';
					}
				}
			}	
		}
		
		// inject a textarea with genericapp.ini content
		if(isset($this->view->intro_warn)) {
			$this->view->after_intro = '
				<h4>As we are nice, we provided you a generic configuration to start with:</h4>
				<div style="text-align:right;">&darr; <small>'.LIBRARY_ABSPATH.'/Peak/Application/genericapp.ini</small></div>
				<textarea spellcheck="false">'.(file_get_contents(LIBRARY_ABSPATH.'/Peak/Application/genericapp.ini')).'</textarea>';
		}
		
		
		$this->view->now = date('Y-m-n H:i:s');
		$this->view->peak = PK_NAME.' v'.PK_VERSION;
		
		$this->layout();
    }
	
		
	/**
	 * Controller View Layout
	 */
	private function layout()
	{
		$twitter_bs = file_get_contents(LIBRARY_ABSPATH.'/Peak/Vendors/TwitterBootstrap/css/bootstrap.min.css');
		$layout = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Welcome to Peak Framework App Welcome Page. Damn! that\'s a long title</title>
    <meta name="description" content="">
    <meta name="author" content="Francky the bad guy">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->

	<link href="http://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css" />
    <style type="text/css">
	  /* TWITTER CSS */
	  '.$twitter_bs.'
	  /* --------------------- */
      body {
        /*padding-top: 60px;*/
		font-family: "Ubuntu", sans-serif;
      }
      .navbar {
        position:inherit;
        margin-bottom:30px;
      }
	  .hero-unit {
		padding:27px 40px;
	  }
	  .hero-unit h1 {
		font-size:42px;
	  }
	  textarea {
	 	  width:100%;
		  height:400px;
		  font-family: "Consolas", "Lucida Console", sans-serif;
	  }
	  small,table small {
		color:#999;
	  }
	  .navbar a {
		color:#000;
		-webkit-transition-delay: 0s;
		-webkit-transition-duration: 0.20000000298023224s;
		-webkit-transition-property: all;
		-webkit-transition-timing-function: linear;
		background-color: transparent;
		color: black;
		cursor: auto;
		display: block;
		float: right;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		height: 20px;
		line-height: 20px;
		margin-bottom: 0px;
		margin-left: 20px;
		margin-right: 0px;
		margin-top: 0px;
		overflow-x: visible;
		overflow-y: visible;
		padding-bottom: 10px;
		padding-left: 0px;
		padding-right: 0px;
		padding-top: 10px;
		text-decoration: none;
		text-shadow: rgba(255, 255, 255, 0.0980392) 0px 1px 0px, rgba(255, 255, 255, 0.121569) 0px 0px 30px;
		
	  }
    </style>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#">Your App</a>
		  <ul class="nav pull-right"><li class="dropdown"><a>{$peak}</a></li></ul>
        </div>
		
      </div>
    </div>

    <div class="container">
			<div class="hero-unit">
        		
				<h1>Welcome, young padawan!</h1>
				<h4>If you see this, it\'s because you have successfully launched your Peak Framework Application
				{$intro_warn}
				</h4>
				
				{$after_intro}
				
				<br />
				<hr />
				<br />
				
				<h4>&darr; You will find here informations about the current environment</h4>
				<table class="table table-striped">
				<thead>
				  <tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
					<td>APPLICATION_ENV</td>
					<td>'.APPLICATION_ENV.'</td>
				  </tr>
				  <tr>
					<td>LIBRARY_ROOT</td>
					<td>'.LIBRARY_ROOT.'<br />
						<small>'.realpath(LIBRARY_ABSPATH).'</small></td>
				  </tr>
				  <tr>
					<td>PUBLIC_ROOT</td>
					<td>'.PUBLIC_ROOT.'<br />
						<small>'.realpath(PUBLIC_ABSPATH).'</small></td>
				  </tr>
				  <tr>
					<td>APPLICATION_ROOT{$application_folder_missing}</td>
					<td>'.APPLICATION_ROOT.'<br />
						<small>'.realpath(APPLICATION_ABSPATH).'</small></td>
				  </tr>
				  <tr>
					<td>APPLICATION_CONFIG</td>
					<td>'.APPLICATION_CONFIG.'<br />
						<small>'.realpath(APPLICATION_ABSPATH).APPLICATION_CONFIG.'</small>
					</td>
				  </tr>
				  
				</tbody>
			  </table>
				
				<div class="clear"></div>
			</div>
	</div>

  </body>
</html>';
		
		$this->view->setLayout($layout);
	}

}