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
    public array $parcelsWithSpaceToTake;

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
We'll call you back as soon, as we find a way to do that\n\n");
            } else {
                $this->parcelsWithSpaceToTake[] = array_fill( 0, sizeof( $this->containers ), null );
                $parcelId = array_key_last( $this->parcelsWithSpaceToTake );

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

    public function arrangePackageInContainers() {
        if( !isset( $this->parcelsWithSpaceToTake ) || !isset( $this->containersAreas ) ) {
            return false;
        }

        if( empty( $packageVariant = $this->calculation->setTheMostOptimalPackage( $this->parcelsWithSpaceToTake, $this->containersAreas ) ) ) {
            echo("Couldn't arrange packaging\n\n");

            return false;
        };

        foreach( $this->containers as $containerId => $container ) {
            $dimensions = join( '*', $container->getDimensions() );
            $packageVariant = str_replace( "id: $containerId", "dimensions: $dimensions", $packageVariant );
        }

        echo( $packageVariant . "\n\n" );

        return true;
    }
}