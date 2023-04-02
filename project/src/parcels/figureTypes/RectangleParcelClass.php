<?php

/**
 * Created by lorik.
 */

namespace src\parcels\figureTypes;

use src\parcels\BaseParcelClass;

require ( str_replace( 'figureTypes', 'BaseParcelClass.php', __DIR__  ) );

class RectangleParcelClass extends BaseParcelClass {
    private int $width;
    private int $length;

    public function __construct($width, $length) {
        $this->width = $width;
        $this->length = $length;
    }

    /**
     * Formula to calculate a rectangle area is "A = w*l"
     *
     * Square form is a specific type of rectangle, so we do not use another formula.
     */
    public function calcArea(): int {
        return $this->width * $this->length;
    }

    public function getDimensions(): array {
        return [
            'width' => $this->width,
            'length' => $this->length
        ];
    }
}


