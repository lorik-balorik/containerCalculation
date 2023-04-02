<?php
/**
 * Created by lorik.
 */

namespace src\calculations;

class PackagingCalculationClass {

    public function checkParcelsDimensions( array $parcelDimensions, array $containersWithDims ) {
        if( !$this->checkIfDataIsValid( $parcelDimensions, $containersWithDims ) ) {
            return false;
        }

        /**
         * If parcel's dimensions don't fit container's dimensions, even if the calculated aria is suitable,
         * don't create this parcel for the transport.
         *
         * For example, if the length of the parcel will be bigger than container's both width and length,
         * we can't put it inside container.
         */
        $containerFitIds = null;
        foreach( $containersWithDims as $oneContainerKey => $oneContainerWithDim ) {

            if( ( $parcelDimensions['width'] <= $oneContainerWithDim['width'] ) || ( $parcelDimensions['length'] <= $oneContainerWithDim['width'] ) ) {
                if( ( $parcelDimensions['width'] <= $oneContainerWithDim['length'] ) || ( $parcelDimensions['length'] <= $oneContainerWithDim['length'] ) ) {

                    $containerFitIds[] = $oneContainerKey;
                }
            }
        }

        return $containerFitIds;
    }
    public function setTheMostOptimalPackage( array $parcels, array $containers ) {
        if( empty( $variationsOfPackages = $this->getVariationsOfPackage( $parcels, $containers ) ) ) {
            return false;
        }

        $bestOptionVal = null;
        $bestOptionDescription = '';

        foreach( $variationsOfPackages as $package ) {
            $currentOptionVal = null;
            $currentOptionDescription = '';

            foreach( $package as $usedContainerId => $usedContainer ) {

                /**
                 * Get ratio of taken space on a boat (united area of containers) and fullness (united areas of used space in containers)
                 * space:fullness
                 */
                $countContainers = ceil( $usedContainer ); // if we use even only a part of the container, we round fractions up. Ex: usage of 0.5 of the container means that we already use 1 container.
                $ratio = ( $containers[ $usedContainerId ] * $countContainers ) / ( $containers[ $usedContainerId ] * $usedContainer );

                $currentOptionVal += $ratio;
                $currentOptionDescription .= "$countContainers containers with id: $usedContainerId; ";

                if ( ( $bestOptionVal == null || $bestOptionVal > $currentOptionVal )
                     && $usedContainerId == array_key_last( $package ) ) {

                    $bestOptionVal = $currentOptionVal;
                    $bestOptionDescription = $currentOptionDescription;
                }
            }
        }

        return $bestOptionDescription;
    }

    private function getVariationsOfPackage( array $parcelsAreas, array $containersAreas ) {
        if( !$this->checkIfDataIsValid( $parcelsAreas, $containersAreas ) ) {
            return false;
        }

        $countContainers = sizeof( $containersAreas );
        $countParcels = sizeof($parcelsAreas); // exponent
        $finalSize = pow( $countContainers, $countParcels ); // final number of variations of permutations

        // To prevent memory overflow
        if( $finalSize > 1000000 ) {
            print("Too many options.\n\nWe'll do reduction\n\n");
            $finalSize = 1000000;
        }

        $usedContainers = [];
        for ( $i = 0; $i < $finalSize; $i++ ) {
            for ( $c = 0; $c < $countParcels; $c++ ) {
                $index = ceil( $i / pow( $countContainers, $c ) ) % $countContainers;

                // if we can put this parcel in a container with this index
                if( isset( $parcelsAreas[ $c ][ $index ] ) ) {

                    // if we already put number of taken space in a container, we sum those numbers
                    if( array_key_exists( $i, $usedContainers ) && array_key_exists( $index, $usedContainers[ $i ] ) ) {
                        $usedContainers[ $i ][ $index ] += $parcelsAreas[ $c ][ $index ];
                    } else {
                        $usedContainers[ $i ][ $index ] = $parcelsAreas[ $c ][ $index ];
                    }
                } else {
                    $usedContainers[ $i ][ $index ] = null;
                }
            }

            // filter impossible variants => if we couldn't put big parcel in small container
            if( in_array( null, $usedContainers[ $i ] ) ) {
                unset( $usedContainers[ $i ] );
            }
        }

        return $usedContainers;
    }

    private function checkIfDataIsValid( $parcelsData, $containersData ): bool {
        if( empty( $parcelsData ) ) {
            echo("Missing data of parcels\n\n");

            return false;
        }
        if( empty( $containersData ) ) {
            echo("Missing data of containers\n\n");

            return false;
        }

        return true;
    }
}