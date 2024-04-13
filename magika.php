<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /");
}

function checkFileExists($target_file) {
    if (file_exists($target_file)) {
        echo "<div class='error'>Sorry, file already exists.</div>";
        return false;
    }
    return true;
}

//As Default 15 MB
function checkFileSize($file_size,$maxSize = 15) {
    if ($file_size > $maxSize * 1000000 ) {
        echo "<div class='error'>Sorry, your file is too large.</div>";
        return false;
    }
    return true;
}

function checkUploadError($uploadOk) {
    if ($uploadOk == 0) {
        echo "<div class='error'>Sorry, your file was not uploaded.</div>";
        return false;
    }
    return true;
}

function uploadAndExecuteCommand($target_dir, $uploaded_file_name)
{
    $target_file = $target_dir . basename($uploaded_file_name);
    $uploadOk = 1;

    // Check if file already exists
    if (!checkFileExists($target_file)) {
        return;
    }

    // Check file size
    if (!checkFileSize($_FILES["fileToUpload"]["size"])) {
        return;
    }

    // Check if $uploadOk is set to 0 by an error
    if (!checkUploadError($uploadOk)) {
        return;
    }

    // Move uploaded file to target directory
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<div class='success'>The file " . htmlspecialchars(basename($uploaded_file_name)) . " has been uploaded.</div>";

        // Execute the command this works with Linux (Debian) servers
        $command = "/usr/local/bin/magika $target_dir/*";
        $result = shell_exec($command);
        //Output in JSON format
        $command_json = "/usr/local/bin/magika --json $target_dir/*";
        $result_json = shell_exec($command_json);

        // Remove the uploaded file to prevent
        unlink($target_file);

        // Extract and display the desired portion of the result   -- starting with filename and ending with )
        $start_index = strpos($result, $uploaded_file_name);
        $end_index = strpos($result, ')[0;', $start_index);
        $extracted_string = substr($result, $start_index, $end_index - $start_index + 1);

        // You can add more css if you want
        echo "<div class='result'>Result: <pre>$extracted_string</pre></div>";
        echo "<div class='result'>infos: <pre>$result_json</pre></div>";

    } else {
        echo "<div class='error'>Sorry, there was an error uploading your file.</div>";
    }
}

// Call the function
$target_dir = "uploads/";
$uploaded_file_name = $_FILES["fileToUpload"]["name"];
uploadAndExecuteCommand($target_dir, $uploaded_file_name);
?>

<style>
    .error {
        color: red;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .success {
        color: green;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .result {
        margin-top: 20px;
    }
</style>
