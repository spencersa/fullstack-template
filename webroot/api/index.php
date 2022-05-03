<?php
 header('Content-Type: application/json');
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Methods: GET');

 $queryString = $_SERVER['QUERY_STRING'];
 parse_str($queryString, $queryStringParameters);

 if ($queryStringParameters["name"]) {
   $resultOkay = true;
   $result = getCountriesName($queryStringParameters["name"], filter_var($queryStringParameters["full-name"], FILTER_VALIDATE_BOOLEAN));
 }
 else if ($queryStringParameters["country-code"]) {
   $resultOkay = true;
   $result = getCountriesCountryCode($queryStringParameters["country-code"]);
 }

 if ($result->statusCode == 200) {
   echo $result->data;
 }
 else if ($result->statusCode == 404) {
   header('HTTP/1.1 404 Not Found');
 }
 else {
   header('HTTP/1.1 500 Oh crap');
 }

 function getCountriesName($name, $fullName)
 {
    $url = "https://restcountries.com/v3.1/name/$name";
    if ($fullName && $fullName == true) {
        $url .= "?fullText=true";
    }
    return apiGet($url);
 }

 function getCountriesCountryCode($countryCode)
 {
    $url = "https://restcountries.com/v3.1/alpha/$countryCode";
    return apiGet($url);
 }

 class Response{
   public $statusCode;
   public $data;
 }

 function apiGet($url)
 {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $responseData = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response = new Response();
    $response->statusCode=$http_status;
    $response->data=$responseData;
    return $response;
 }