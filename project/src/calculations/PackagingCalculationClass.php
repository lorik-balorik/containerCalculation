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

    public function getTheMostOptimalPackage( array $parcelsAreas, array $containersAreas ) {
        if( !$this->checkIfDataIsValid( $parcelsAreas, $containersAreas ) ) {
            return false;
        }

        $countContainers = sizeof( $containersAreas );
        $countParcels = sizeof($parcelsAreas); // exponent
        $finalSize = pow( $countContainers, $countParcels ); // final number of variations of permutations

        $bestOptionVal = null;
        $bestOptionDescription = '';
        $packageOptions = [];
        for ( $i = 0; $i < $finalSize; $i++ ) {
            $currentOptionVal = null;
            $currentOptionDescription = '';
//            $currentOptionParcelPlacing = '';

            for ( $c = 0; $c < $countParcels; $c++ ) {
                $index = ceil( $i / pow( $countContainers, $c ) ) % $countContainers;

                if( isset( $parcelsAreas[ $c ][ $index ] ) ) {

                    // if we already put number of taken space in a container, we sum those numbers
                    if( array_key_exists( $i, $packageOptions ) && array_key_exists( $index, $packageOptions[ $i ] ) ) {
                        $packageOptions[ $i ][ $index ] += $parcelsAreas[ $c ][ $index ];
                    } else {
                        $packageOptions[ $i ][ $index ] = $parcelsAreas[ $c ][ $index ];
                    }

                    // if use printing of parcel's placing in which container, provide time latency

//                    $currentOptionParcelPlacing = "parcel with parcelId: $c in container with id: $index;\n\n";
                } else {
                    $packageOptions[ $i ][ $index ] = null;
                }
            }

            if( !in_array( null, $packageOptions[ $i ] ) ) { // don't do for impossible variants => if we couldn't put big parcel in small container
                foreach( $packageOptions[ $i ] as $usedContainerId => $usedContainer ) {

                    /**
                     * Get ratio of taken space on a boat (united area of containers) and fullness (united areas of used space in containers)
                     * space:fullness
                     */
                    $countContainers = ceil( $usedContainer ); // if we use even only a part of the container, we round fractions up. Ex: usage of 0.5 of the container means that we already use 1 container.
                    $ratio = ( $containersAreas[ $usedContainerId ] * $countContainers ) / ( $containersAreas[ $usedContainerId ] * $usedContainer );

                    $currentOptionVal += $ratio;
                    $currentOptionDescription .= "$countContainers containers with id: $usedContainerId; ";

                    if ( ( $bestOptionVal == null || $bestOptionVal > $currentOptionVal )
                        && $usedContainerId == array_key_last( $packageOptions[ $i ] ) ) {

                        $bestOptionVal = $currentOptionVal;
                        $bestOptionDescription = $currentOptionDescription . " optionId is: $i;\n\n"; // . $currentOptionParcelPlacing;
                    }
                }
            }

            // unset checked option to prevent memory leak
            unset( $packageOptions[ $i ] );
        }

        return $bestOptionDescription;
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