<?php
if (isset($_POST['submit'])) 
{        
    // register autoload
	include_once(APPPATH . '/libraries/Padl/Padl.php');
    Padl::registerAutoload();

    // gets the data and transform to boolean
    $domain		= $_POST['domain'];
    $useMcrypt  = $_POST['useMcrypt']  == 'true' ?  true : false;
    $useTime    = $_POST['useTime']    == 'true' ?  true : false;
    $useServer  = $_POST['useServer']  == 'true' ?  true : false;
    $allowLocal = $_POST['allowLocal'] == 'true' ?  true : false;

    // calculates the offset (expire_in)
    $now		= mktime(date('H'), date('i'), date('s'), date('m'), date('d') , date('Y'));
    $dateLimit  = mktime(23, 59, 59, 
            $_POST['dateLimitMonth'], 
            $_POST['dateLimitDay'], 
            $_POST['dateLimitYear']);
    $expireIn = $dateLimit - $now;

    // instatiate the class
    $padl = new Padl\License($useMcrypt, $useTime, $useServer, $allowLocal);

    // copy the server vars (important for security, see note below)
    $server_array = $_SERVER;

    // set the server vars
    // note this doesn't have to be set, however if not all of your app files are encoded
    // then there would be a possibility that the end user could modify the server vars
    // to fit the key thus making it possible to use your app on any domain
    // you should copy your server vars in the first line of your active script so you can
    // use the unmodified copy of the vars
    $padl->setServerVars($server_array);
    
    // generate a key with your server details
    $license = $padl->generate($domain, 0, $expireIn);
    
    header("Content-Type: application/save");
    header("Content-Length:".strlen($license)); 
    header('Content-Disposition: attachment; filename="' . $_POST['filename'] . '"'); 
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0'); 
    header('Pragma: no-cache');
    echo $license;
    exit;
} ?>  
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Venus I-Medis3 Distribution Licensing</title>
        <meta name="description" content="PHP Aplication Distribution Licensing">
        <meta name="author" content="Rafael Goulart">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="css/bootstrap-1.2.0.min.css" rel="stylesheet">
        <link href="css/application.css" rel="stylesheet">
    </head>
    <body>
    <div class="topbar">
        <div class="fill">
            <div class="container">
				<h3><a href="index.php">Generate Key File for Venus I-Medis3</a></h3>
            </div>
        </div>
    </div>
    <div class="container">
		<div class="hero-unit" style="padding: 0;"></div>
        <form action="" method="post">
            <fieldset> 
                <legend>Data for Instatiate Padl\License Class</legend> 
                <div class="clearfix"> 
                    <label for="useMcrypt">Use MCript</label> 
                    <div class="input"> 
                        <select name="useMcrypt" id="useMcrypt"> 
                            <option value="true">true</option> 
                            <option value="false">false</option> 
                        </select>
                    </div> 
                </div> 
                <div class="clearfix"> 
                    <label for="useTime">Use Time</label> 
                    <div class="input"> 
                        <select name="useTime" id="useTime"> 
                            <option value="true">true</option> 
                            <option value="false">false</option> 
                        </select>
                    </div> 
                </div> 
                <div class="clearfix"> 
                    <label for="useServer">Use server</label> 
                    <div class="input"> 
                        <select name="useServer" id="useServer"> 
                            <option value="true">true</option> 
                            <option value="false">false</option> 
                        </select>
                    </div> 
                </div> 
                <div class="clearfix"> 
                    <label for="allowLocal">Allow Local</label> 
                    <div class="input"> 
                        <select name="allowLocal" id="allowLocal"> 
                            <option value="false">false</option> 
                            <option value="true">true</option> 
                        </select>
                    </div> 
                </div> 
            </fieldset> 
            <fieldset> 
                <legend>Data for Padl\License::generate Method</legend>
                <div class="clearfix"> 
                    <label for="domain">Domain</label> 
                    <div class="input"> 
                        <input class="xlarge" id="domain" name="domain" type="text" placeholder="domain without http:// or localhost" />
                    </div> 
                </div> 
                <div class="clearfix"> 
                    <label>Date Limit</label> 
                    <div class="input"> 
                        <div class="inline-inputs"> 
                            <select class="small" name="dateLimitMonth" id="dateLimitMonth"> 
                                <option value="1">January</option> 
                                <option value="2">February</option> 
                                <option value="3">March</option> 
                                <option value="4">April</option> 
                                <option value="5">May</option> 
                                <option value="6">June</option> 
                                <option value="7">July</option> 
                                <option value="8">August</option> 
                                <option value="9">September</option> 
                                <option value="10">October</option> 
                                <option value="11">November</option> 
                                <option value="12">December</option> 
                            </select> 
                            <select class="mini" name="dateLimitDay" id="dateLimitDay"> 
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select> 
                            <select class="mini" name="dateLimitYear" id="dateLimitYear"> 
								<option value="2013">2013</option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
								<option value="2017">2017</option>
								<option value="2018">2018</option>
								<option value="2019">2019</option>
								<option value="2020">2020</option>
								<option value="2021">2021</option>
								<option value="2022">2022</option>
								<option value="2023">2023</option>
							</select>
                        </div> 
                    </div> 
                </div>         
                <div class="clearfix"> 
                    <label for="filename">File Name</label> 
                    <div class="input"> 
                        <input class="xlarge" id="filename" name="filename" type="text" value="i-medis3.lic" />
                    </div> 
                </div> 
                <div class="actions"> 
                    <button type="submit" class="btn primary" name="submit">Submit and Generate Key</button>&nbsp;
                    <button type="reset" class="btn">Reset</button> 
                </div> 
            </fieldset>
        </form>
    </div> <!-- /container -->
    <footer style="text-align: center">
		<p>
			Copyright &COPY; 2013 <a href="http://tech.rgou.net">Venus Media Teknologi</a>
		</p>
    </footer>
  </body>
</html>