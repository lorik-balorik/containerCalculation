<?php
/**
 * Created by lorik.
 */

require( __DIR__ . '/src/transports/TransportClass.php' );
require( __DIR__ . '/src/calculations/PackagingCalculationClass.php' );
require ( __DIR__ . '/src/containers/containerTypes/RectangleContainerClass.php' );
require( __DIR__ . '/src/parcels/figureTypes/RectangleParcelClass.php' );
require( __DIR__ . '/src/parcels/figureTypes/CircleParcelClass.php' );

use src\calculations\PackagingCalculationClass;
use src\containers\containerTypes\RectangleContainerClass;
use src\transports\TransportClass;
use src\parcels\figureTypes\CircleParcelClass;
use src\parcels\figureTypes\RectangleParcelClass;

$start = hrtime(true);

$bigContainer = new RectangleContainerClass(300, 200);
$smallContainer = new RectangleContainerClass(100, 100);

echo("Transport 1\n\n");
$transport1 = new TransportClass( new PackagingCalculationClass );
$transport1->setNewContainers( $smallContainer, $bigContainer );
$transport1->setNewParcels(
    new CircleParcelClass(50, 'circle'),
    new CircleParcelClass(50, 'circle'),
    new RectangleParcelClass(100, 100, 'square'),
);
$transport1->arrangePackageInContainers();

echo("Transport 2\n\n");
$transport2 = new TransportClass( new PackagingCalculationClass );
$transport2->setNewContainers( $smallContainer, $bigContainer );
$transport2->setNewParcels(
    new RectangleParcelClass(400, 400, 'square'),
    new CircleParcelClass(100, 'circle'),
);
$transport2->arrangePackageInContainers();


echo("Transport 3\n\n");
$transport3 = new TransportClass( new PackagingCalculationClass );
$transport3->setNewContainers( $smallContainer, $bigContainer );
$transport3->setNewParcels(
    new RectangleParcelClass(150, 100, 'rectangle'),
    new RectangleParcelClass(50, 50, 'square'),
    new CircleParcelClass(50, 'circle'),
);
$transport3->arrangePackageInContainers();


$end = hrtime(true);
$time = ($end - $start) / 1000000000;
echo "runtime: " . $time . "\n\n";   // Seconds