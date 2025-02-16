/*
Handling Large Data Efficiently
You need to process a large CSV file (5GB) and insert data into a database. How would you do this efficiently in PHP?
*/

//Read the CSV in Chunks & Use Batch Inserts.
//Instead of loading everything at once, we read the file line by line and insert data into the database in batches.

<?php
$filename = "large_file.csv";
$batchSize = 1000; // Insert 1000 rows at a time
$rows = [];
$rowCount = 0;

$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Open the file for reading
if (($handle = fopen($filename, "r")) !== FALSE) {
    fgetcsv($handle); // Skip the header row

    $sql = "INSERT INTO users (name, email, age) VALUES ";
    $placeholders = [];
    $values = [];

    while (($data = fgetcsv($handle)) !== FALSE) {
        $placeholders[] = "(?, ?, ?)";
        array_push($values, $data[0], $data[1], $data[2]);

        $rowCount++;

        if ($rowCount % $batchSize == 0) {
            $stmt = $pdo->prepare($sql . implode(", ", $placeholders));
            $stmt->execute($values);
            $placeholders = [];
            $values = [];
        }
    }

    // Insert any remaining data
    if (!empty($placeholders)) {
        $stmt = $pdo->prepare($sql . implode(", ", $placeholders));
        $stmt->execute($values);
    }

    fclose($handle);
}

echo "Data import completed.";
?>
