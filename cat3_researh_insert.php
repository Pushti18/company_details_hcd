<?php
session_start();
include("db_connect.php");

$project_category = isset($_POST['projectCategory']) ? $_POST['projectCategory'] : '';
$project_for = isset($_POST['projectFor']) ? $_POST['projectFor'] : '';
$pbas_year = isset($_POST['year']) ? $_POST['year'] : '';
$project_duration = isset($_POST['projectDuration']) ? $_POST['projectDuration'] : '';
$funding_agency = isset($_POST['fundingAgency']) ? $_POST['fundingAgency'] : '';
$grant_amount = isset($_POST['grantAmount']) ? $_POST['grantAmount'] : '';
$approval_copy = isset($_FILES['approvalCopy']['name']) ? $_FILES['approvalCopy']['name'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
global $conn;

$pbasScore = 0;

if ($project_category == 'Above 30 Lakhs') {
    $pbasScore = 20; 
} elseif ($project_category == '5-30 Lakhs') {
    $pbasScore = 15; 
} elseif ($project_category == '1-5 Lakhs') {
    $pbasScore = 10; 
}

if ($project_for == 'Consultancy') {
    $amount_mobilized = floatval($grant_amount);
    
    if ($amount_mobilized >= 1000000) {
        $pbasScore += floor($amount_mobilized / 100000) * 10;
    } elseif ($amount_mobilized >= 200000) {
        $pbasScore += floor($amount_mobilized / 100000) * 2;
    }
}

$sql = "INSERT INTO research (project_category, project_for, pbas_year, project_duration,title, funding_agency, grant_amount, approval_copy_path, pbas_score) 
        VALUES ('$project_category', '$project_for', '$pbas_year', '$project_duration', '$title','$funding_agency', '$grant_amount', '$approval_copy', '$pbasScore')";
mysqli_query($conn, $sql);

if (mysqli_error($conn)) {
    echo "Error: " . mysqli_error($conn);
} else {
    echo "Data stored successfully. PBAS Score: $pbasScore";
}

mysqli_close($conn);

?>