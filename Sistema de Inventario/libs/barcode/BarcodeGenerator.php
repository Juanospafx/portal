<?php

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

    /**
     * @param string $code
     * @param string $type
     * @param int $widthFactor
     * @param int $height
     * @param array $foregroundColor
     */
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

    /**
     * @return bool
     */
    protected function getBarcodeData()
    {
        return $this->barcodeData;
    }
}
