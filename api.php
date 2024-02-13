<?php
require_once __DIR__ . "/lib/MphpD/MphpD.php";

use FloFaber\MphpD\MphpD;
use FloFaber\MphpD\MPDException;

// Constants
define('MPD_HOST', 'localhost');
define('MPD_PORT', 6600);
define('MPD_PASSWORD', '');

// Global variables
$output_arr = array();

/***********************************************************************/

// Load settings file
$settings_json = file_get_contents("settings.json");
$settings_data = json_decode($settings_json, true);

// Load channel data
$stations = array();
foreach ($settings_data["stations"] as &$station) {
    $stations[$station["url"]] = $station["name"];
}

// Configure and initialize MphpD client library
$mphpd = new MphpD([
    "host" => MPD_HOST,
    "port" => MPD_PORT,
    "timeout" => 5
]);

try {
   $mphpd_connected = $mphpd->connect();
} catch (MPDException $e) {
    $output_arr['error'] = $e->getMessage();
    $output_arr['status'] = 'ERROR';
}

// Process API calls
if ($mphpd_connected) {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $parameters = isset($_GET['parameters']) ? $_GET['parameters'] : array();

        switch ($action) {
            case 'get_status':
                $status = $mphpd->status();
                $output_arr['content'] = $status;
                $output_arr['status'] = 'OK';
                break;
            case 'get_volume':
                $volume = $mphpd->player()->volume();
                
                $output_arr['content'] = $volume;
                $output_arr['status'] = 'OK';
                break;
            case 'get_songinfo':

                $songinfo = $mphpd->player()->current_song();
                if ($songinfo) {
                    $output_arr['content'] = array(
                        'song' => $songinfo['title'],
                        'streamurl' => (isset($stations[$songinfo['file']]) ?  $stations[$songinfo['file']] : $songinfo['file'])
                    );
                } else {
                    $output_arr['content'] = array(
                        'song' => 'PAUSED', 
                        'streamurl' => 'Please select a channel'
                    );
                }
                
                $output_arr['status'] = 'OK';
                break;
            case 'set_station':
                if (isset($parameters['streamurl'])) {
                    $streamurl = $parameters['streamurl'];

                    $mphpd->queue()->clear();
                    $mphpd->queue()->add($streamurl);
                    $mphpd->player()->play(0);

                    $output_arr['content'] = print_r($parameters['streamurl'], true);
                    $output_arr['status'] = 'OK';
                } else {
                    $output_arr['error'] = 'no streamurl provided';
                    $output_arr['status'] = 'ERROR';
                }
                break;
            case 'set_volume':
                if (isset($parameters['volume'])) {
                    $volume = $parameters['volume'];

                    $mphpd->player()->volume(intval($volume));
                } else {
                    $output_arr['error'] = 'no valid volume level provided';
                    $output_arr['status'] = 'ERROR';
                }
                $output_arr['content'] = '';
                $output_arr['status'] = 'OK';
                break;
            default:
                $output_arr['error'] = 'action doesn\'t exist';
                $output_arr['status'] = 'ERROR';
        }

    } else {
        $output_arr['error'] = 'no action defined';
        $output_arr['status'] = 'ERROR';
    }
} else {
    $output_arr['error'] = 'MPD client not connected';
    $output_arr['status'] = 'ERROR';
}

$output = json_encode($output_arr);
echo $output;

if ($output_arr['status'] == 'ERROR') {-
    http_response_code(500);
}


