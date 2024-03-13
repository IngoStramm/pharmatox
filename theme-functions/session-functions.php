<?php
add_action('init', 'pt_init_session');

function pt_init_session()
{
    if (!session_id()) {
        session_start();
    }
}
