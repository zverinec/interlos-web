<?php
date_default_timezone_set('Europe/Prague');
if(new DateTime() >= new DateTime("2017-12-10 20:00:59")) {
	@rename(".htaccess",'.htaccess-old');
} else {
	die('Riešenia budú sprístupnené až o 20:01.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="content-language" content="cz" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
  <meta name="description" content="InterLoS - Internetova Logicka Soutez" />
  <link href="design/stylesheet.css" media="screen" rel="stylesheet" type="text/css" />
  <link rel="icon" href="design/favicon.ico" type="image/x-icon" />
  <title>INTERnetová LOgická SOutěž</title>

</head>
<div class="root">
			<div class="header">
		<div class="header-line">&nbsp;</div>
		<div class="contents">
			<a href="http://interlos.fi.muni.cz/">
				<span class="block header-logo" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
				<span class="block header-name" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
			</a>
			<div class="header-year"><h1>ročník 2017</h1></div>
			<div class="cleaner-both">&nbsp;</div>
		</div>
		</div>
		
	<div class="main">
	<div class="contents">
		<div class="main-block">
			<h1>Řešení</h1>
		<p>Všechna (textová řešení) v jednom PDF souboru: <a href="reseni.pdf">reseni.pdf</a></p>
		<h2>Přiložené soubory</h2>
		<ul>
			<li><a href="https://github.com/zverinec/interlos-web/blob/master/public/download/years/2017/reseni/P1s-solution.php">P1s-solution.php</a> (P1 Losí utkání)</li>
			<li><a href="P2s-solution.py">P2s-solution.py</a> (P2 Los s ručením omezeným)</li>
			<li><a href="P3s-solution.py">P3s-solution.py</a> (P3 Sekvence)</li>
			<li><a href="P4s-solution.py">P4s-solution.py</a> (P4 Galton)</li>
			<li><a href="P5s-solution.py">P5s-solution.py</a> (P5 Losí úřady)</li>
			<li><a href="P6s-solution.py">P6s-solution.py</a> (P6 Wiki)</li>
			<li><a href="P7s-solution.py">P7s-solution.py</a> (P7 Batoh)</li>
			<li><a href="P8s-reseni.cpp">P8s-reseni.cpp</a> (P8 Mooze)</li>
			<li><a href="P9s-solution.py">P9s-solution.py</a> (P9 Transformácia)</li>
		</ul>

		<div class="hr">&nbsp;</div>
		<p>Veškeré problémy s úlohami a odevzdávacím systémem hlaste z e-mailové adresy uvedené v profilu týmu na adresu <a href="mailto:interlos@fi.muni.cz"><span class="bold">interlos@fi.muni.cz</span></a>.
		V předmětu e-mailu musí být uvedeno ID týmu . Svoje ID také najdete <a href="http://interlos.fi.muni.cz/">na stránce soutěže</a> v záhlaví stránky vedle jména.</p>
		</div>
	</div>
	</div>

			<div  class="footer">
		<div class="footer-line">&nbsp;</div>
		</div>
	 
</div>
</body>
</html>
