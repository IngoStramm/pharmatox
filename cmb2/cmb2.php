<?php
if (!pt_check_if_plugin_is_active('cmb2/init.php')) {
    return;
}

require_once(PT_DIR . '/cmb2/cmb-user.php');
require_once(PT_DIR . '/cmb2/cmb-settings.php');
require_once(PT_DIR . '/cmb2/cmb-post-type.php');
require_once(PT_DIR . '/cmb2/cmb-tax.php');
