<?php
/**
 * Created by lorik.
 *
 * Because the problem is about applying combinatorics theory (permutations with repeats), don't put enormous number of either containers and parcels.
 * Ex: 4 containers' types and 11 parcels => 4^11 => 4,194,304 variants
 *
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
    new CircleParcelClass(50),
    new CircleParcelClass(50),
    new RectangleParcelClass(100, 100),
);
$transport1->arrangePackageInContainers();

echo("Transport 2\n\n");
$transport2 = new TransportClass( new PackagingCalculationClass );
$transport2->setNewContainers( $smallContainer, $bigContainer );
$transport2->setNewParcels(
    new RectangleParcelClass(400, 400),
    new CircleParcelClass(100),
);
$transport2->arrangePackageInContainers();


echo("Transport 3\n\n");
$transport3 = new TransportClass( new PackagingCalculationClass );
$transport3->setNewContainers( $smallContainer, $bigContainer );
$transport3->setNewParcels(
    new RectangleParcelClass(150, 100),
    new RectangleParcelClass(50, 50),
    new CircleParcelClass(50),
);
$transport3->arrangePackageInContainers();


$end = hrtime(true);
$time = ($end - $start) / 1000000000;
echo "runtime: " . $time . "\n\n";   // Seconds