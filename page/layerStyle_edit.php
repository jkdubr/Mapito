<?php
$layer = new LGLayer($_GET['layerId']);
$layerStyleManager = new LGLayerStyleManager($user);
?>

Layer: <?php echo($layer->title); ?>

<form action="action/layer_update.php" method="POST" data-ajax="false">
    <input type="hidden" name="layerId" value="<?php echo($layer->layerId); ?>">

    <div data-role="fieldcontain">
        <fieldset data-role="controlgroup">

            <?php
            //<legend>Choose a pet:</legend>

            foreach ($layerStyleManager->getStylesByUser() as $layerStyleId) {
                $layerStyle = new LGLayerStyle($layerStyleId);
                ?>
                <input type="radio" name="layerStyleId" id="radio-choice-<?php echo($layerStyle->layerStyleId); ?>" value="<?php echo($layerStyle->layerStyleId); ?>" <?php echo($layerStyle->layerStyleId == $layer->layerStyleId ? "checked" : ""); ?>/>
                <label for="radio-choice-<?php echo($layerStyle->layerStyleId); ?>"><?php echo($layerStyle->title); ?></label>

            <?php } ?>
        </fieldset>
    </div>
    <a href="index.php?page=layers_edit">Return to layers</a>
    <input type="submit" value="Save">
</form>