<?php
	$desc = "<p>encontre os &uacute;ltimos epis&oacute;dios das suas s&eacute;ries em poucos cliques com o <b>busca.s&eacute;rie</b></p>";

	$show_name = str_replace(" ", "+", "" . addslashes($_POST["show_name"]) . "");
	$quality = addslashes($_POST["quality"]);
	
	//echo $show_name . "<br />";
	//echo $quality;
	
	if($_POST["submit"]) {

		if(empty($show_name)) {

			$desc = "<p>por favor, preencha todos os campos antes de prosseguir.</p>";

		} else {
			
			$rssURL = "http://ezrss.it/search/index.php?show_name=" . $show_name . "&show_name_exact=true&quality=" . $quality . "&mode=rss";
			$xml = simplexml_load_file($rssURL);
			//print_r($xml);

			//if($xml) {
				$desc =  "
	<table>
		<tr>
			<td>epis&oacute;dio</td>
			<td>publicado em </td>
			<td>baixar magnet (recomendado)</td>
			<td>baixar .torrent</td>
		</tr>
				";

				foreach($xml->channel->item as $item) {

				$hash = $item->torrent->infoHash;
				$filename = str_replace(".[eztv].torrent].", ".mkv", $item->torrent->fileName);

					$desc .= "
		<tr>
			<td>" . $item->title . "</td>
			<td>" . date('d-m-Y', strtotime($item->pubDate)) ."</td>
			<td><a href=\"" . $item->torrent->magnetURI ."\"><img src=\"magnet.png\" alt=\"\" /></a></td>
			<td><a href=\"" . $item->link ."\"><img src=\"torrent.png\" alt=\"\" /></a></td>
		</tr>
					";

				}

				$desc .= "
	<table>
				";

			//} else {

			//	$desc = "desculpe, mas a sua busca n&atilde;o retornou resultados. verifique o nome da s&eacute;rie ou modifique a qualidade.";

			//}

		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>busca.s&eacute;rie</title>
	<link rel="icon" type="image/png" href="favicon.png" />
	<link rel="stylesheet" type="text/css" href="reset.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<!--[if lt IE 8]>
	<style type="text/css">
	#submit {
		position: relative;
		top: 8px; }
	</style>
	<![endif]-->
	<?php
		if($_POST["submit"]) {
			echo "
	<style type=\"text/css\">
		#container { margin-top: 1%; margin-bottom: 30px; }
	</style>";
		}
	?>
</head>
<body>
<div id="container">
	<div id="logo"></div>
	<form method="POST">
		<p><input type="text" name="show_name" placeholder="s&eacute;rie" />&nbsp;
		<select name="quality">
			<option disabled="disabled">-- qualidade --</option>
			<option value="HDTV">hdtv</option>
			<option value="DSR">dsr</option>
			<option value="720p">hd (720p)</option>
			<option value="">todas</option>
		</select>&nbsp;
		<input type="submit" id="submit" name="submit" value="buscar" /></p>
	</form>
	<div id="sub"><?php echo $desc; ?></div>
</body>
</html>