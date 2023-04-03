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

$transportTest = new TransportClass( new PackagingCalculationClass );
$transportTest->setNewContainers(
    $smallContainer,
    $bigContainer,
    new RectangleContainerClass(50, 50),
    new RectangleContainerClass(150, 150),
//    new RectangleContainerClass(200, 200)
);

$transportTest->setNewParcels(
    new CircleParcelClass(100, 'circle'),
    new RectangleParcelClass(400, 400, 'square'),
    new CircleParcelClass(50, 'circle'),
    new CircleParcelClass(40, 'circle'),
    new CircleParcelClass(20, 'circle'),
    new CircleParcelClass(15, 'circle'),
    new CircleParcelClass(5, 'circle'),
    new CircleParcelClass(30, 'circle'),
    new CircleParcelClass(60, 'circle'),
    new CircleParcelClass(30, 'circle'),
    new RectangleParcelClass(90, 90, 'square'),
//    new RectangleParcelClass(9, 50, 'rectangle'),
//    new RectangleParcelClass(20, 30, 'rectangle'),
//    new RectangleParcelClass(60, 80, 'rectangle'),
//    new RectangleParcelClass(25, 14, 'rectangle'),
//    new RectangleParcelClass(110, 110, 'square'),
);

$transportTest->arrangePackageInContainers();

$end = hrtime(true);
$time = ($end - $start) / 1000000000;
echo "runtime: " . $time . "\n\n";   // Seconds