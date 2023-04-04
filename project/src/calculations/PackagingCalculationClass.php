<?php
/**
 * Created by lorik.
 */

namespace src\calculations;

class PackagingCalculationClass {

    /**
     * If parcel's dimensions don't fit container's dimensions, even if the calculated aria is suitable,
     * don't create this parcel for the transport.
     *
     * For example, if the length of the parcel will be bigger than container's both width and length,
     * we can't put it inside container.
     */
    public function checkParcelsDimensions( array $parcelDimensions, array $containersWithDims ) {
        if( !$this->checkIfDataIsValid( $parcelDimensions, $containersWithDims ) ) {
            return false;
        }

        $containerFitIds = null;
        foreach( $containersWithDims as $oneContainerKey => $oneContainerWithDim ) {

            if( ( $parcelDimensions['width'] <= $oneContainerWithDim['width'] ) || ( $parcelDimensions['width'] <= $oneContainerWithDim['length'] ) ) {
                if( ( $parcelDimensions['length'] <= $oneContainerWithDim['length'] ) || ( $parcelDimensions['length'] <= $oneContainerWithDim['width'] ) ) {

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
        $countParcels = sizeof($parcelsAreas); /** Exponent */
        $finalSize = pow( $countContainers, $countParcels ); /**  Final variations' number of permutations */

        $bestOptionVal = null;
        $bestOptionDescription = '';
        $packageOptions = [];
        for ( $i = 0; $i < $finalSize; $i++ ) {
            $currentOptionVal = null;
            $currentOptionDescription = '';
//            $currentOptionParcelPlacing = '';

            for ( $c = 0; $c < $countParcels; $c++ ) {
                $index = intval( $i / pow( $countContainers, $c ) ) % $countContainers;

                if( isset( $parcelsAreas[ $c ][ $index ] ) ) {

                    /**
                     * If we already put an amount of taken space in a container, we sum those numbers
                     */
                    if( array_key_exists( $i, $packageOptions ) && array_key_exists( $index, $packageOptions[ $i ] ) ) {
                        if( isset( $packageOptions[ $i ][ $index ] ) ) {
                            $packageOptions[ $i ][ $index ] += $parcelsAreas[ $c ][ $index ];
                        }
                    } else {
                        $packageOptions[ $i ][ $index ] = $parcelsAreas[ $c ][ $index ];
                    }

                    /**
                     * If we use printing in which container place a parcel; provide time latency
                     */
//                    $currentOptionParcelPlacing .= "parcel $c with parcelId: $c in a container with id: $index;\n\n";
                } else {
                    $packageOptions[ $i ][ $index ] = null;
                }
            }

            if( !in_array( null, $packageOptions[ $i ] ) ) { /** don't do for impossible variants => if we couldn't put big parcel in small container */
                foreach( $packageOptions[ $i ] as $usedContainerId => $usedContainer ) {

                    /**
                     * Get ratio of taken space on a boat (united area of containers) and fullness (united areas of used space in containers)
                     * space:fullness
                     *
                     * If we use even only a part of the container, we round fractions up. Ex: usage of 0.5 of the container means that we already use 1 container.
                     */
                    $countRequiredContainers = ceil( $usedContainer );
                    $ratio = ( $containersAreas[ $usedContainerId ] * $countRequiredContainers ) / ( $containersAreas[ $usedContainerId ] * $usedContainer );

                    $currentOptionVal += $ratio;
                    $currentOptionDescription .= "$countRequiredContainers containers with id: $usedContainerId; ";

                    if ( ( $bestOptionVal == null || $bestOptionVal > $currentOptionVal )
                        && $usedContainerId == array_key_last( $packageOptions[ $i ] ) ) {

                        $bestOptionVal = $currentOptionVal;
                        $bestOptionDescription = $currentOptionDescription . " optionId is: $i;\n\n"; // . $currentOptionParcelPlacing;
                    }
                }
            }

            /** Unset checked option to prevent memory leak */
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