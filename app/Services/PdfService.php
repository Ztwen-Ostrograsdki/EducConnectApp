<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;

class PdfService
{
    /**
     * Create a configured Browsershot instance.
     *
     * @return Browsershot
     */
    public static function make(): Browsershot
    {
        return Browsershot::html('')
            ->setNodeBinary(env('BROWSERSHOT_NODE_PATH', 'node'))
            ->setChromePath(env('BROWSERSHOT_CHROME_PATH'))
            ->noSandbox()
            ->dismissDialogs()
            ->waitUntilNetworkIdle();
    }

    /**
     * Generate a PDF from HTML content.
     *
     * @param string $html
     * @param string $path
     * @return string — saved file path
     */
    public static function fromHtml(string $html, string $path): string
    {
        static::make()
            ->setHtml($html)
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->save($path);

        return $path;
    }

    /**
     * Generate a PDF from a Blade view.
     *
     * @param string $view
     * @param array $data
     * @param string $path
     * @return string — saved file path
     */
    public static function fromView(string $view, array $data, string $path): string
    {
        $html = view($view, $data)->render();
        return static::fromHtml($html, $path);
    }
}