<?php

$environment = config("app.env");
if($environment == "production")
{
    $document_url = "storage/app/public/";
    $upload_document_url = "public/";
} else if($environment == "local"){
    $document_url = "storage/app/public/";
    $upload_document_url = "";
}

return[
    "DOCUMENT_URL" => $document_url,
    "UPLOAD_DOCUMENT_URL" => $upload_document_url,
];
