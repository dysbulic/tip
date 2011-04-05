<?php

function getvar($name) {
        return isset($_POST[$name])
                 ? $_POST[$name]
                 : ( isset($_GET[$name])
                       ? $_GET[$name]
                       : false );
}
