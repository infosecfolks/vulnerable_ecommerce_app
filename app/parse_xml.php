<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_catalog = $_POST['product_catalog'];

    // XXE Vulnerability: Parse the product catalog XML without disabling external entity loading
    libxml_disable_entity_loader(false);
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($product_catalog, "SimpleXMLElement", LIBXML_NOENT);
    if ($xml === false) {
        echo "Invalid XML input.";
        foreach (libxml_get_errors() as $error) {
            echo "<br>", $error->message;
        }
    } else {
        echo $xml->asXML();
    }
    libxml_clear_errors();
}
?>
