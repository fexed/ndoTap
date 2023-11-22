<?php
    $imgfilename = $_GET['name'];

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
    header("Access-Control-Max-Age", "3600");
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    header("Access-Control-Allow-Credentials", "true");

    echo file_get_contents('php://input');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['x'])) {
        echo json_encode(['status' => 'success', 'message' => 'Message received: ' . $data['x'] .' '. $data['y']]);
        // $myfile = fopen($imgfilename."clicked", "a") or die("Unable to open file!");
        $txt = $data['x'] .':'. $data['y']."\n";
        fwrite($myfile, $txt);
        fclose($myfile);
        $command = escapeshellcmd('python visualize.py '.$imgfilename);
        $output = shell_exec($command);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No message received']);
    }
?>