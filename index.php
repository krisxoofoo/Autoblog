<?php

define('ROOT_DIR', __DIR__);

function escape($str)
{
    return htmlspecialchars($str, ENT_COMPAT, 'UTF-8', false);
}

if( !empty($_POST) ) {
    $error = array();
    if( !empty($_POST['sitename']) && !empty($_POST['siteurl']) && !empty($_POST['rssurl']) && !empty($_POST['number']) ) {
        $sitename = escape($_POST['sitename']);
        $siteurl = escape($_POST['siteurl']);
        $rssurl = escape($_POST['rssurl']);
        if( $_POST['number'] != '22' ) {
            $error[] = "Vous êtes un robot si vous ne savez pas taper '2'";
        } 
    }
    else {
        $error[] = "Assurez vous de bien remplir tous les champs.";
    }

    if( empty($error) ) {
        if( !preg_match('#\.\.|/#', $sitename) ) {
            if ( mkdir('./'. $sitename, 0755, false) ) {
                $fp = fopen('./'. $sitename .'/index.php', 'w+');
                if( !fwrite($fp, "<?php require_once dirname(__DIR__) . '/autoblog.php'; ?>") )
                    $error[] = "Impossible d'écrire le fichier index.php";
                fclose($fp);
                $fp = fopen('./'. $sitename .'/vvb.ini', 'w+');
                if( !fwrite($fp, '[VroumVroumBlogConfig]
			SITE_TITLE="Autoblog de '. $sitename .'"
			SITE_DESCRIPTION="Ce site n\'est pas le site officiel de '. $sitename .'<br>C\'est un blog automatis&eacute; qui r&eacute;plique les articles de <a href="'. $siteurl .'">'. $sitename .'</a>"
			SITE_URL='. $siteurl .'
			FEED_URL="'. $rssurl .'"
			DOWNLOAD_MEDIA_FROM='. $siteurl) )
                    $error[] = "Impossible d'écrire le fichier vvb.ini";
                fclose($fp);
            }
            else 
                $error[] = "Impossible de créer le répertoire.";
        }
        else 
            $error[] = "Nom de site invalide.";
    }
}

echo '<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Auto-blogs</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<header id="toppage">
	<h1>Auto-blogs</h1>
	<a href="#modal" class="btn go">Ajouter un site</a>
	<p>Par effet Streisand</p>
</header>
';
if( !empty( $error )) {
    echo '<div id="error"><p>Erreur(s) :</p><ul>';
    foreach ( $error AS $value ) {
        echo '<li>'. $value .'</li>';
    }
    echo '</ul></div>';
}
echo '
	<aside id="modal" class="content">
		<header><h2>Ajouter un site</h2></header>
		<section>
			<p>Si vous souhaitez que nous répliquions un autoblog de votre site, remplissez le formulaire ci-dessous.</p>
			<form id="addwebsite" method="POST">
				<input type="text" name="sitename" id="sitename" placeholder="Nom du site" size="50"><input type="text" name="siteurl" id="siteurl" placeholder="URL du site" size="50"><br>
				<input type="text" name="rssurl" id="rssurl" placeholder="URL du flux RSS" size="50"><input type="text" name="number" id="number" placeholder="Ecrivez vingt-deux en chiffre"  size="50"><br>
				<p class="txtcenter"><input type="submit" value="Créer" ></p>
			</form>
		</section>
		<footer class="cf">
			<a href="#" class="btn">Close</a>
		</footer>
	</aside>
';
echo  '<section>
	<h2>Voici une liste des autoblogs que nous répliquons :</h2>';

$dir = dir(ROOT_DIR);

while ($file = $dir->read())
{
    if ($file[0] == '.')
        continue;

    if (is_dir(ROOT_DIR . '/' . $file)
        && file_exists(ROOT_DIR . '/' . $file . '/vvb.ini'))
    {
        $ini = parse_ini_file(ROOT_DIR . '/' . $file . '/vvb.ini');
        $config = new stdClass;

        foreach ($ini as $key=>$value)
        {
            $key = strtolower($key);
            $config->$key = $value;
        }

        unset($ini);

        echo '
        <article class="vignette">
            <div class="title">
                <h2><a href="'.escape($file).'/" title="Se rendre sur la réplique des articles de '.escape($file).'">'.escape($file).'</a></h2>
                <h4>Depuis <a href="'.escape($config->site_url).'" title="Visiter le site '.escape($file).'">'.escape($config->site_url).'</a></h4>
            </div>
            <div class="content">
                <ul>
                    <li><a href="'.escape($file).'/" title="Se rendre sur la réplique des articles">Auto-blog</a></li>
                    <li><a href="'.escape($file).'/vvb.ini" title="Obtenir le fichier de configuration">Configuration</a></li>
                    <li><a href="'.escape($file).'/articles.db" title="Obtenir le fichier de sauvegarde des articles">Articles</a></li>
                </ul>
            </div>
        </article>';
    }
}

$dir->close();

echo '
</section>
<footer>
	<p>Autoblogs propulsés par VroumVroumBlog inspiré de la version de <a href="http://wiki.hoa.ro/doku.php?id=web%3Aferme-autoblog" title="Autoblog VroumVroumBlog et effet Streisand">Ferme d\'Autoblog</a> (Arthur Hoaro, Creative Commons by 3.0)<br><a href="http://sebsauvage.net/streisand.me/">Plus d\'infos sur le projet</a></p>
</footer>
</body>
</html>';
?>