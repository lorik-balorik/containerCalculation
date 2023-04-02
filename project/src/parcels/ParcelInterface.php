<?php

namespace src\parcels;
/**
 * Created by lorik.
 *
 * Interface to work with whatever figure (parcel) type.
 */
interface ParcelInterface {

    /**
     * @return int
     *
     * There is announced a method to calculate an aria for figures.
     * The implementation will be in a class, which has to implement this interface.
     */
    public function calcArea(): int;

    public function getDimensions(): array;

}