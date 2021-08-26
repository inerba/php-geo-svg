<?php

namespace PrinsFrank\PhpGeoSVG;

use PrinsFrank\PhpGeoSVG\MultiPolygon\MultiPolygon;
use PrinsFrank\PhpGeoSVG\MultiPolygon\MultiPolygonRenderer;
use PrinsFrank\PhpGeoSVG\Projection\Projection;

class GeoSVGRenderer
{
    public const XMLNS   = 'http://www.w3.org/2000/svg';
    public const VERSION = '1.1';

    public function __construct(private GeoSVG $geoSVG) { }

    public function render(Projection $projection): string
    {
        return
            '<svg ' .
                'xmlns="' . self::XMLNS . '" ' .
                'version="' . self::VERSION . '" ' .
                'width="' .  $projection->getMaxX() - $projection->getMinX() . '" ' .
                'height="' . $projection->getMaxY() - $projection->getMinY() . '" ' .
                'preserveAspectRatio="xMidYMid slice" ' .
                'viewBox="' . $projection->getMinX() . ' ' . $projection->getMinY() . ' '
                    . $projection->getMaxX() - $projection->getMinX() . ' '
                    . $projection->getMaxY() - $projection->getMinY() . '">'. PHP_EOL .
                '<g class="countries" transform="' . $projection->getCoordinatesTransformation() . '">' . PHP_EOL .
                    implode(PHP_EOL, array_map(static function (MultiPolygon $multiPolygon) use ($projection) {
                        return MultiPolygonRenderer::render($projection, $multiPolygon);
                    }, $this->geoSVG->multiPolygons)) . PHP_EOL .
                '</g>' . PHP_EOL .
            '</svg>'
        ;
    }
}
