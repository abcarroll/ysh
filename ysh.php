#!/usr/bin/env php
<?php
$configFile = $_SERVER['HOME'] . '/.ysh.ini';
$data = parse_ini_file($configFile, true);

if(empty($argv[1])) {
    $dataKeys = array_keys($data);
    echo "Available: \n";
    echo implode(', ', $dataKeys) . "\n";
    echo " > ";
    $input = fgets(STDIN);
    $input = trim($input);
} else {
    $input = trim($argv[1]);
}

if(!empty($argv[2]) && $argv[2] == '-p') {
    $printMode = true;
} else {
    $printMode = false;
}

if(!empty($argv[2]) && $argv[2] == '--new') {
    $newMode = true;
} else {
    $newMode = false;
}


foreach($data as $key => $part) {
    $primaryKey = strtolower($key);
    if(isset($part['alias'])) {
        $aliasKey = explode(',', $part['alias']);
    }
    $allKey = $aliasKey;
    $allKey[] = $primaryKey;

    if(in_array($input, $allKey)) {
        if($printMode) {
            foreach($part as $pKey => $pVal) {
                echo "$pKey=$pVal\n";
            }
            exit;
        } else {
            $host = $part['host'];

            if(empty($part['port'])) {
                $port = 22;
            } else {
                $port = (int) $part['port'];
            }

            if(!empty($part['user'])) {
                $user = $part['user'] . '@';
            } else {
                $user = '';
            }

            // $exec = "guake -n guake -e 'ssh -p $port $user$host' guake -r '$primaryKey ssh'; guake -t;";

            if($newMode || true) {
                $exec = "guake -n guake -e 'ssh -p $port $user$host' guake -r '$primaryKey ssh';";
            } else {
                $exec = "ssh -p $port $user$host; guake -r '$primaryKey ssh';";
            }


            echo " > Connecting to $primaryKey ($host:$port)\n";
            echo " >> $exec\n\n";

            exec($exec);
            exit;
        }
    }
}

echo "\nNo matching configuration keys found!\n";
