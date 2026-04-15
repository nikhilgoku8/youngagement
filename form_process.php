<?php
// error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 1);

session_start();
date_default_timezone_set('Asia/Calcutta');

$error_flag = 0;
$statusCode = 200;
$form_errors_array = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["fname"])) {
        $form_errors_array['fname'] = "Name is required";
        $error_flag = 1;
    } else {
        $fname = test_input($_POST["fname"]);
    }

    if (empty($_POST["phone"])) {
        $form_errors_array['phone'] = "Phone is required";
        $error_flag = 1;
    } else {
        $phone = test_input($_POST["phone"]);

        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $form_errors_array['phone'] = "Phone number must be exactly 10 digits";
            $error_flag = 1;
        }
    }

    if (empty($_POST["email"])) {
        $form_errors_array['email'] = "Email is required";
        $error_flag = 1;
    } else {
        $email = strtolower( test_input($_POST["email"]) );
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $form_errors_array['email'] = "Invalid email format"; 
          $error_flag = 1;
        }
    }

    if (empty($_POST["business_name"])) {
        $form_errors_array['business_name'] = "Business Name is required";
        $error_flag = 1;
    } else {
        $business_name = test_input($_POST["business_name"]);
    }

    if (empty($_POST["industry"])) {
        $form_errors_array['industry'] = "Industry is required";
        $error_flag = 1;
    } else {
        $industry = test_input($_POST["industry"]);
    }

    if (empty($_POST["social_media"])) {
        // $form_errors_array['social_media'] = "Social Media Handles is required";
        // $error_flag = 1;
    } else {
        $social_media = test_input($_POST["social_media"]);
    }

    if (empty($_POST["goals"])) {
        $form_errors_array['goals'] = "Goals is required";
        $error_flag = 1;
    } else {
        $goals = test_input($_POST["goals"] ?? '');
    }

    if ($error_flag == 0) {

        $postFields = "&entry.1126435178=" .$fname;
        $postFields .= "&entry.685181768=" .$phone;
        $postFields .= "&entry.1450459808=" .$email;
        $postFields .= "&entry.902309291=" .$business_name;
        $postFields .= "&entry.1305045244=" .$industry;
        $postFields .= "&entry.368433916=" .$social_media;
        $postFields .= "&entry.1915883803=" .$goals;


        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://docs.google.com/forms/u/0/d/e/1FAIpQLSdFll7Eo68jL_as3xPpahZ4SRfVbT81iJJ1Q-dcfLv3Y__d7A/formResponse");
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        //print_r($hash);
        $result1 = curl_exec($ch1);  
        
        $response = [
            'success' => ['message' => 'success']
        ];

    } else {
        $response = ['error' => ['error_type' => 'form', 'errors' => $form_errors_array]];
        $statusCode = 422;
    }

} else {
    $response = ['error' => ['message' => 'Invalid request']];
    $statusCode = 422;
}

header('Content-Type: application/json; charset=UTF-8');
http_response_code($statusCode);
echo json_encode($response);

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}



?>