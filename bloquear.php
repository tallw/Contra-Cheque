<?php
function trancar_pagina($nome){
if (eregi("$nome", $_SERVER['SCRIPT_NAME'])){
die ("<script>alert('Sem permição de acesso !')</script>");
}
}
?>