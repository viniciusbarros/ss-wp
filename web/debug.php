<?php

if( isset($_GET['k']) && $_GET['k'] == 'aiolos'){
    echo '<pre>';
    print_r($_SERVER);
    print_r(getenv());
}