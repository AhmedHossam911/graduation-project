<?php

namespace App\Traits;

trait DocumentManagerTrait
{
    /**
     * Clean filename by removing invalid characters.
     *
     * @param string $fileName
     * @return string
     */
    protected function cleanFilename($fileName)
    {
        return str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);
    }

    /**
     * Send a document response either inline (view) or as a download.
     *
     * @param string $path
     * @param string $fileName
     * @param bool $download
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function sendDocumentResponse($path, $fileName, $download = false)
    {
        if (!file_exists($path)) {
            abort(404);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $cleanName = $this->cleanFilename($fileName);

        if ($download) {
            return response()->download($path, "{$cleanName}.{$extension}");
        }

        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . $cleanName . '.' . $extension . '"'
        ]);
    }
}
