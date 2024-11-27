<?php
date_default_timezone_set('Europe/Prague');
if(new DateTime() >= new DateTime("2018-12-09 16:30:00")) {
	@rename(".htaccess",'.htaccess-old');
} else {
	die('Druhá sada bude sprístupnená až o 16:30.');
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
			<div class="header-year"><h1>ročník 2018</h1></div>
			<div class="cleaner-both">&nbsp;</div>
		</div>
		</div>

	<div class="main">
	<div class="contents">
		<div class="main-block">
			<h1>Zadání 2. sady</h1>
		<p>Všechna (textová zadání) v jednom PDF souboru: <a href="sada2.pdf">sada2.pdf</a></p>
		<h2>Přiložené soubory</h2>
		<ul>

			<li><a href="L4-navigace.mp3" target="_blank">L4-navigace.mp3</a> (L4 Rozbitá navigace)</li>
			<li><a href="P4-losless.txt" download="P4-losless.txt">P4-losless.txt</a> (P4 Losless compression)</li>
			<li><a href="P5-reduce.txt" download="P5-reduce.txt">P5-reduce.txt</a> (P5 Zásobník na kafe)</li>
			<li><a href="P6-slova.txt" download="P6-slova.txt">P6-slova.txt</a> (P6 Poskladaj reťazec)</li>
			<li><a href="S5-mapa.png" target="_blank">S5-mapa.png</a> (S5 Katastrofická)</li>
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
