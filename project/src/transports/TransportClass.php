<?php
/**
 * Created by lorik.
 */

namespace src\transports;

use src\calculations\PackagingCalculationClass;
use src\containers\BaseContainerClass;
use src\parcels\BaseParcelClass;

class TransportClass {
    private PackagingCalculationClass $calculation;
    public array $containers = [];
    public array $containersAreas = [];
    public array $parcels = [];
    public array $parcelsWithSpaceToTake = [];

    public function __construct( PackagingCalculationClass $calculation) {
        $this->calculation = $calculation;
    }

    public function setNewContainers( BaseContainerClass ...$newContainers ) {
        $this->containers = $newContainers;
        foreach( $this->containers as $container ) {
            $this->containersAreas[] = $container->calcArea();
        }
    }

    public function setNewParcels( BaseParcelClass ...$newParcels ) {
        $containersDimensions = [];
        foreach( $this->containers as $container ) {
            $containersDimensions[] = $container->getDimensions();
        }

        foreach( $newParcels as $newParcel ) {
            $containerFitIds = $this->calculation->checkParcelsDimensions( $parcelDimensions = $newParcel->getDimensions(), $containersDimensions );

            if( empty( $containerFitIds ) ) {
                echo("Sorry, at the moment we can't transport a parcel with such dimensions (width: {$parcelDimensions['width']}, length: {$parcelDimensions['length']}).  
We'll call you back as soon, as we find a way to do that.\n
Do check for other parcel.\n\n");
            } else {
                $this->parcels[] = $newParcel;
                $parcelId = array_key_last( $this->parcels );

                foreach( $containerFitIds as $id ) {
                    $parcelArea = $newParcel->calcArea();

                    $this->parcelsWithSpaceToTake[ $parcelId ][ $id ] = $parcelArea / $this->containersAreas[ $id ];
                }
            }
        }

        if( empty( $this->parcelsWithSpaceToTake ) ) {
            echo("Couldn't arrange a transport for provided parcels. Check please dimensions of your goods\n\n");
        }
    }

    public function arrangePackageInContainers(): bool {
        if( !isset( $this->parcelsWithSpaceToTake ) || !isset( $this->containersAreas ) ) {
            return false;
        }

        if( empty( $packageVariant = $this->calculation->getTheMostOptimalPackage( $this->parcelsWithSpaceToTake, $this->containersAreas ) ) ) {
            echo("Couldn't arrange packaging\n\n");

            return false;
        };

        foreach( $this->containers as $containerId => $container ) {
            $dimensions = join( '*', $container->getDimensions() );
            $packageVariant = str_replace( "id: $containerId", "dimensions: $dimensions", $packageVariant );
        }

        /** If you use printing of parcel's placing in which container; provide time latency */
//        foreach( $this->parcels as $parcelId => $parcel ) {
//            $dimensions = join( '*', $parcel->getDimensions() );
//            $packageVariant = str_replace( "parcelId: $parcelId", "dimensions: $dimensions, type: {$parcel->getType()},", $packageVariant );
//        }

        echo( $packageVariant . "\n\n" );

        return true;
    }
}