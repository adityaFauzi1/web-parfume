<?php

namespace Database\Seeders;

trait GeneratesPlaceholderImages
{
    /**
     * Generate a simple SVG placeholder image.
     */
    protected function makePlaceholder(string $path, string $title, string $colorA, string $colorB): void
    {
        $dir = dirname($path);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
          <defs>
            <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%" stop-color="{$colorA}"/>
              <stop offset="100%" stop-color="{$colorB}"/>
            </linearGradient>
          </defs>
          <rect width="600" height="600" fill="url(#bg)"/>
          <circle cx="300" cy="280" r="110" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="3"/>
          <path d="M300 200 L345 280 L300 360 L255 280 Z" fill="rgba(255,255,255,0.9)"/>
          <text x="300" y="452" font-family="Georgia, serif" font-size="34" fill="#ffffff" text-anchor="middle" font-weight="bold">{$title}</text>
          <text x="300" y="490" font-family="Georgia, serif" font-size="18" fill="rgba(255,255,255,0.85)" text-anchor="middle" letter-spacing="4">MAISON PARFUM</text>
        </svg>
        SVG;

        file_put_contents($path, $svg);
    }
}