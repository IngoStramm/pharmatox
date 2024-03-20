<?php
#region Constants
define('PT_DIR', get_template_directory());
define('PT_URL', get_template_directory_uri());

#endregion Constants

#region Classes

require_once(PT_DIR . '/classes/classes.php');

#endregion Classes

#region Requires

// Theme Functions
require_once(PT_DIR . '/theme-functions/theme-functions.php');

// CMB2
require_once(PT_DIR . '/cmb2/cmb2.php');

// Style/Scripts include
require_once(PT_DIR . '/scripts.php');

#endregion Requires

#region Debug

// add_action('wp_head', 'pt_test');

function pt_test()
{
    // $empresa_obj = pt_get_empresa();
    // $empresa_logo_file = get_attached_file($empresa_obj->logo_id);
    pt_debug(get_the_date($post_id));
}

#endregion Debug