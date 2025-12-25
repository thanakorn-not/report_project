<?php
require 'config/config.php';
$check = ['records_module5','module5_activities'];
foreach($check as $t){
    echo "Table: $t\n";
    echo str_repeat('-',40)."\n";
    try{
        $cols = $pdo->query("DESCRIBE {$t}")->fetchAll(PDO::FETCH_ASSOC);
        foreach($cols as $c){
            echo "{$c['Field']} - {$c['Type']}" . PHP_EOL;
        }
    }catch(PDOException $e){
        echo "ERROR: " . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
}
