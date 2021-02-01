<?php
if (isset($_SESSION['message'])){
    $mess = json_decode($_SESSION['message']);
    echo '<div class="alert alert-'.$mess->status.'" role="alert">'.$mess->message.'</div>';
    unset($_SESSION['message']);
}
