<?php
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="employee-data.csv"');

    $data = $_GET;

    $output = fopen('php://output', 'w');

    fputcsv($output, array_keys($data));

    fputcsv($output, $data);

    fclose($output);
?>