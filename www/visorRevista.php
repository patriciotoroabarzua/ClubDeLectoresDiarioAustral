<?php
include '../config/config.php';
alertaMaxima();
$revistas=getRevista();
$fields["revistaTemuco"]=ordenarRevista(getRevistaCiudad($revistas,"temuco"));
$fields["revistaValdivia"]=ordenarRevista(getRevistaCiudad($revistas,"valdivia"));
$fields["revistaOsorno"]=ordenarRevista(getRevistaCiudad($revistas,"osorno"));
$fields["revistaPuertoMontt"]=ordenarRevista(getRevistaCiudad($revistas,"puerto montt"));

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("searchlog.html", "../templates/inc");
$obj_page->add_all($fields, "");
$search = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$search, $fp);

$obj_page                  = new page_class("header-index.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);



$obj_page                  = new page_class("visorRevista.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body."</body>" , $fp);

$obj_page                  = new page_class("footer.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin, $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Revistas PassClub</title>", $fp);
$fp = str_replace('onload="loadX()"', "", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>