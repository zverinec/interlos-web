<?php
date_default_timezone_set('Europe/Prague');
if(new DateTime() >= new DateTime("2017-12-10 15:00:00")) {
    @rename(".htaccess",'.htaccess-old');
} else {
    die('Prvá sada bude sprístupnená až o 15:00.');
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
            <h1>Zadání 1. sady</h1>
        <p>Všechna (textová zadání) v jednom PDF souboru: <a href="sada1.pdf">sada1.pdf</a></p>
        <h2>Přiložené soubory</h2>
        <ul>
            <li><a href="P2-lossro.txt">P2-lossro.txt</a> (P2 Los s ručením omezeným)</li>
            <li><a href="P3-sekvence-a.txt">P3-sekvence-a.txt</a> (P3 Sekvence)</li>
            <li><a href="P3-sekvence-b.txt">P3-sekvence-b.txt</a> (P3 Sekvence)</li>
            <li><a href="S3-nyanlos.gif">S3-nyanlos.gif</a> (S3 NyanLos)</li>
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
