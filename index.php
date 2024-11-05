<?php

// Fonction pour convertir DOMdocument en tableau
function domToArray($node) {
    $output = [];
    foreach ($node->childNodes as $child) {
        if ($child->nodeType === XML_TEXT_NODE && trim($child->nodeValue) !== '') {
            return htmlspecialchars(trim($child->nodeValue));
        } elseif ($child->nodeType === XML_ELEMENT_NODE) {
            $childValue = domToArray($child);
            if (isset($output[$child->nodeName])) {
                if (!is_array($output[$child->nodeName]) || !isset($output[$child->nodeName][0])) {
                    $output[$child->nodeName] = [$output[$child->nodeName]];
                }
                $output[$child->nodeName][] = $childValue;
            } else {
                $output[$child->nodeName] = $childValue;
            }
        }
    }
    return $output;
}

// Fonction pour convertir un tableau en DOMdocument
function arrayToXmlDOM($data, $dom, &$xmlData) {
    foreach ($data as $key => $value) {
        if (is_numeric($key)) {
            $key = 'item';
        }

        $subnode = $dom->createElement($key);
        $xmlData->appendChild($subnode);

        if (is_array($value)) {
            arrayToXmlDOM($value, $dom, $subnode);
        } else {
            $subnode->appendChild($dom->createTextNode(htmlspecialchars($value)));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'], $_POST['format'])) {
    $file = $_FILES['file'];
    $format = $_POST['format'];
    $uploadDir = 'uploads/';
    $destination = $uploadDir . basename($file['name']);

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        echo "Erreur lors de l'upload du fichier";
        exit;
    }

    if ($format === 'xml-to-json' && pathinfo($file['name'], PATHINFO_EXTENSION) === 'xml') {
        $dom = new DOMDocument();
        $dom->load($destination);
        $array = domToArray($dom->documentElement);
        $json = json_encode($array, JSON_PRETTY_PRINT);
        $outputFile = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.json';
        file_put_contents($outputFile, $json);
        echo "Conversion XML vers JSON réussie. <a href='$outputFile' download>Télécharger le fichier converti</a>";

    } elseif ($format === 'json-to-xml' && pathinfo($file['name'], PATHINFO_EXTENSION) === 'json') {
        $jsonContent = file_get_contents($destination);
        $array = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Erreur JSON : " . json_last_error_msg();
            exit;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $root = $dom->createElement('root');
        $dom->appendChild($root);
        arrayToXmlDOM($array, $dom, $root);
        $outputFile = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.xml';
        file_put_contents($outputFile, $dom->saveXML());
        echo "Conversion JSON vers XML réussie. <a href='$outputFile' download>Télécharger le fichier converti</a>";
    } else {
        echo json_encode(['error' => 'Format incorrect.']);
    }
}
?>
