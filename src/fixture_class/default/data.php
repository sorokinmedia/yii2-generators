<?php
use yii\web\View;
use sorokinmedia\gii\generators\fixture_class\Generator;

/** @var $this View */
/** @var $generator Generator */
/** @var $items array */

echo "<?php\n";
?>
return [
<?php foreach ($items as $item): ?>
    [
<?php foreach ($item as $name => $value): ?>        '<?= $name ?>' => <?= $value ?>,
<?php endforeach; ?>
    ],
<?php endforeach; ?>
];