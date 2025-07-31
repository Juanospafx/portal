<?php
require_once('includes/load.php');

// Barcode generation library
namespace Picqer\Barcode;

class BarcodeGenerator
{
    const TYPE_CODE_39 = 'C39';
    const TYPE_CODE_39_CHECKSUM = 'C39+';
    const TYPE_CODE_39E = 'C39E';
    const TYPE_CODE_39E_CHECKSUM = 'C39E+';
    const TYPE_CODE_93 = 'C93';
    const TYPE_STANDARD_2_5 = 'S25';
    const TYPE_STANDARD_2_5_CHECKSUM = 'S25+';
    const TYPE_INTERLEAVED_2_5 = 'I25';
    const TYPE_INTERLEAVED_2_5_CHECKSUM = 'I25+';
    const TYPE_CODE_128 = 'C128';
    const TYPE_CODE_128_A = 'C128A';
    const TYPE_CODE_128_B = 'C128B';
    const TYPE_CODE_128_C = 'C128C';
    const TYPE_EAN_2 = 'EAN2'; // 2-digit supplemental
    const TYPE_EAN_5 = 'EAN5'; // 5-digit supplemental
    const TYPE_EAN_8 = 'EAN8';
    const TYPE_EAN_13 = 'EAN13';
    const TYPE_UPC_A = 'UPCA';
    const TYPE_UPC_E = 'UPCE';
    const TYPE_MSI = 'MSI'; // MSI (Variation of Plessey code)
    const TYPE_MSI_CHECKSUM = 'MSI+'; // MSI with checksum (modulo 10)
    const TYPE_POSTNET = 'POSTNET';
    const TYPE_PLANET = 'PLANET';
    const TYPE_RMS4CC = 'RMS4CC'; // Royal Mail 4-state Customer Code
    const TYPE_KIX = 'KIX'; // KIX (Klant index - Customer index)
    const TYPE_IMB = 'IMB'; // Intelligent Mail Barcode
    const TYPE_CODABAR = 'CODABAR';
    const TYPE_CODE_11 = 'CODE11';
    const TYPE_PHARMA_CODE = 'PHARMA';
    const TYPE_PHARMA_CODE_TWO_TRACKS = 'PHARMA2T';

    protected $barcodeData;

    protected function setBarcodeData($code, $type, $widthFactor = 2, $height = 30, $foregroundColor = array(0, 0, 0))
    {
        $this->barcodeData = array(
            'code' => $code,
            'type' => $type,
            'widthFactor' => $widthFactor,
            'height' => $height,
            'foregroundColor' => $foregroundColor
        );
    }

    protected function getBarcodeData()
    {
        return $this->barcodeData;
    }
}

class BarcodeGeneratorPNG extends BarcodeGenerator
{
    public function getBarcode($code, $type, $widthFactor = 2, $height = 30, $foregroundColor = array(0, 0, 0))
    {
        $this->setBarcodeData($code, $type, $widthFactor, $height, $foregroundColor);
        $image = imagecreate($this->getBarcodeWidth(), $height);
        $color1 = imagecolorallocate($image, $foregroundColor[0], $foregroundColor[1], $foregroundColor[2]);
        $color2 = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $color2);
        $this->drawBarcode($image, $color1, 0, 0);
        ob_start();
        imagepng($image);
        $image_data = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        return $image_data;
    }

    private function getBarcodeWidth()
    {
        $barcodes = $this->getBarcodeData();
        $width = 0;
        foreach ($barcodes['bars'] as $bar) {
            $width += $bar['width'] * $this->barcodeData['widthFactor'];
        }
        return $width;
    }

    private function drawBarcode($image, $color, $x, $y)
    {
        $barcodes = $this->getBarcodeData();
        foreach ($barcodes['bars'] as $bar) {
            $bar_width = $bar['width'] * $this->barcodeData['widthFactor'];
            if ($bar['draw']) {
                imagefilledrectangle($image, $x, $y, $x + $bar_width - 1, $this->barcodeData['height'] - 1, $color);
            }
            $x += $bar_width;
        }
    }
}

$product_id = find_by_id('products', (int)$_GET['id']);
if(!$product_id){
  page_require_level(2);
  $session->msg("d", "Missing Product id.");
  redirect('product.php');
}

$generator = new BarcodeGeneratorPNG();
header('Content-Type: image/png');
echo $generator->getBarcode($product_id['barcode'], $generator::TYPE_CODE_128);

?>