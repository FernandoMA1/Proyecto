<?php
$directorio_uploads = 'uploads/chat/';
$directorio_uploads = 'uploads/ticket/';

if (!file_exists($directorio_uploads)) {
    if (mkdir($directorio_uploads, 0777, true)) {
        echo "Directorio '$directorio_uploads' creado exitosamente.<br>";
    } else {
        echo "Error al crear el directorio '$directorio_uploads'.<br>";
    }
} else {
    echo "El directorio '$directorio_uploads' ya existe.<br>";
}


$htaccess_content = "# Permitir solo ciertos tipos de archivos
<FilesMatch \"\\.(jpg|jpeg|png|gif|webp|mp4|webm|ogg)$\">
    Order Allow,Deny
    Allow from all
</FilesMatch>

# Denegar acceso a archivos PHP
<FilesMatch \"\\.php$\">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Prevenir ejecución de scripts
Options -ExecCGI
AddHandler cgi-script .php .pl .py .jsp .asp .sh .cgi";

$htaccess_file = $directorio_uploads . '.htaccess';
if (file_put_contents($htaccess_file, $htaccess_content)) {
    echo "Archivo .htaccess creado en '$htaccess_file'.<br>";
} else {
    echo "Error al crear el archivo .htaccess.<br>";
}

echo "Configuración completada.";
?>