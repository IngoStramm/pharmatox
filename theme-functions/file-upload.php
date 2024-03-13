<?php
add_filter('upload_mimes', 'my_myme_types', 1, 1);

function my_myme_types($mime_types)
{
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['pfx'] = 'application/x-pkcs12';
    return $mime_types;
}
