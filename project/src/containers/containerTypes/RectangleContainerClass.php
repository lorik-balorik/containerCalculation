<?php
/**
 * Created by lorik.
 */

namespace src\containers\containerTypes;

use src\containers\BaseContainerClass;

require ( str_replace( 'containerTypes', 'BaseContainerClass.php', __DIR__  ) );


class RectangleContainerClass extends BaseContainerClass {
    private int $width;
    private int $length;

    public function __construct($width, $length) {
        $this->width = $width;
        $this->length = $length;
    }

    /**
     * Formula to calculate a container area is "A = w*l" as for a rectangle.
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