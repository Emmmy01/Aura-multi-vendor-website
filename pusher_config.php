
<?php
require 'vendor/autoload.php';

use Pusher\Pusher;

function pusher() {
    return new Pusher(
        '4cc374d32f9266493ece',        // Replace with your Pusher app key
        'a8f8be4e9fce987d988d',     // Replace with your Pusher app secret
        '1978098',         // Replace with your Pusher app ID
        [
            'cluster' => 'mt1', // Replace with your cluster
            'useTLS' => true
        ]
    );
}
?>
