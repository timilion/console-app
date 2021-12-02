<?php

namespace app\components;


use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Yii;
use yii\base\InvalidConfigException;

class Convert
{
    private string $path;
    private array $extensions;

    /**
     * @param string $path
     * @param array $extensions
     */
    public function __construct(string $path, array $extensions)
    {
        $this->path = $path;
        $this->extensions = $extensions;
    }


    public function run(): void
    {
        $directory = new \RecursiveDirectoryIterator($this->path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $info) {
            try {
                $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
                if (in_array($extension, $this->extensions, true)) {
                    $this->createPdfFile($info->getPathname());
                }
            } catch (\Throwable $e) {
                Yii::error($e->getMessage(), 'convertPdfFile');
                continue;
            }
        }
    }


    /**
     * @param string $path
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    private function createPdfFile(string $path): void
    {
        $content = file_get_contents($path);
        $file = $path . '.pdf';
        if (!file_exists($file)) {
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'content' => $content,
                'filename' => $file
            ]);
            $pdf->render();
        }
    }
}