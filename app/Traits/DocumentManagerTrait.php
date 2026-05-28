<?php

namespace App\Traits;

trait DocumentManagerTrait
{
    /**
     * Sanitize a given filename by stripping out any characters that are invalid or unsafe for file systems.
     *
     * @param string $fileName
     * @return string
     */
    protected function cleanFilename($fileName)
    {
        return str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);
    }

    /**
     * Construct and return the appropriate HTTP response for a document.
     * It handles formatting the headers so the file is either displayed inline in the browser or downloaded.
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
