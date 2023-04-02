<?php

namespace src\containers;
/**
 * Created by lorik.
 *
 * Interface to work with whatever figure (container) type.
 */
interface ContainerInterface {

    /**
     * @return int
     *
     * There is announced a method to calculate an area for figures.
     * The implementation will be in a class, which has to implement this interface.
     */
    public function calcArea()
    : int;

    public function getDimensions(): array;
}