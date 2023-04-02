<?php

/**
 * Created by lorik.
 */

namespace src\parcels\figureTypes;

use src\parcels\BaseParcelClass;

class CircleParcelClass extends BaseParcelClass {
    private int $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    /**
     * Assume that parcel with circle form takes up the space as a square parcel.
     * Because we can't put anything between circles: the possibility that we will find
     * a tiny enough figure almost equals null.
     *
     * Formula to calculate square area is "A = l^2", in that case we assume that "l = 2R".
     *
     * So we'll do calculations by formula "A = (2R)^2 = 4(R)^2", not as for circle formula "2piR^2"
     */
    public function calcArea(): int {
        return 4 * pow( $this->radius, 2 );
    }

    public function getDimensions(): array {
        return [
            'width' => $this->radius * 2,
            'length' => $this->radius * 2
        ];
    }
}