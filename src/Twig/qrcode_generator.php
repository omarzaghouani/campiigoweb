<?php

namespace App\Twig;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class QrCodeGenerator extends AbstractExtension
{
    public function generateQrCode(string $text): string
    {
        $builder = new Builder();
        $builder->writer(new PngWriter())
            ->data($text)
            ->encoding(new Encoding('UTF-8'))
            ->size(100)
            ->margin(10)
            ->labelText('');
    
        $qrCode = $builder->build();
    
        return $qrCode->getDataUri();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generateQrCode', [$this, 'generateQrCode']),
        ];
    }
}

