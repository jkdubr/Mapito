<?php
if (!$user->isPrivilegeSuperAdmin()) {
    exit("no privilege");
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>












<?php
$layerManager = new LGLayerManager($user);
?>




<div  data-role="collapsible-set">


    <div data-role="collapsible">
        <h3>Add public layer to map</h3>
        <form action="action/layer_add.php" method="POST"  data-ajax="false">
            <input type="hidden" name="type" value="wms" />
            <div data-role="fieldcontain">
                <label for="layerPublic_add_layer">Layer:</label>
                <input id="layerPublic_add_layer" type="text" name="title" placeholder="Layer title">
            </div>


            <div data-role="fieldcontain">
                <label for="layers_add_url">WMS URL:</label>
                <input id="layers_add_url" type="url" name="url">
            </div>

            <div data-role="fieldcontain">
                <label for="layers_add_namespace">Namespace:</label>
                <input id="layers_add_namespace"  type="text" name="namespace">
            </div>

            <div data-role="fieldcontain">
                <label for="layers_add_name">Layer name:</label>
                <input id="layers_add_name" type="text" name="name" >
            </div>

            <input type="hidden" name="planId" value="0">
            <input type="submit" value="Add layer">
        </form>
    </div>









    <?php
    foreach ($layerManager->getLayersByPlan($plan->planId) as $layerId) {

        $layer = new LGLayer($layerId);
        ?>

        <div data-role="collapsible" id="layer_edit_<?php echo($layer->layerId); ?>"  <?php if ($layerId == (int) $_GET["layerId"]) { ?> data-collapsed="false" <?php } ?>>
            <h3><?php echo($layer->title); ?></h3>

            <form action="action/layer_update.php" method="POST" data-ajax="false"  enctype="multipart/form-data">
                <input type="hidden" name="layerId" value="<?php echo($layer->layerId); ?>">
                <input type="hidden" name="planId" value="<?php echo($layer->planId); ?>">



                <div data-role="fieldcontain">
                    <label for="layers_edit_title">Name:</label>
                    <input id="layers_edit_title" type="text" name="title" value="<?php echo($layer->title); ?>">
                </div>

                <div data-role="fieldcontain">
                    <label for="layers_edit_namespace">Namespace:</label>
                    <input id="layers_edit_namespace"  type="text" name="namespace" value="<?php echo($layer->namespace); ?>">
                </div>


                <div data-role="fieldcontain">
                    <label for="layers_edit_name">Layer name:</label>
                    <input id="layers_edit_name"  type="text" name="name" value="<?php echo($layer->name); ?>">
                </div>

                <div data-role="fieldcontain">
                    <label for="layers_edit_txt">Description:</label>
                    <input id="layers_edit_txt" type="text" name="txt" value="<?php echo($layer->txt); ?>">
                </div>

                <div data-role="fieldcontain">
                    <label for="layers_edit_url">WMS URL:</label>
                    <input id="layers_edit_url" type="url" name="url"  value="<?php echo($layer->url); ?>">
                </div>


                <input type="submit" value="<?php if ($layer->new) { ?>Add layer<?php } else { ?>Save<?php } ?>" data-theme="">


                <input type="button" value="Remove layer" data-theme="a" onclick="if(window.confirm('Remove layer?')){document.location='action/layer_remove.php?layerId=<?php echo($layer->layerId); ?>'}"> 

            </form>

        </div>
    <?php } ?>

</div>  
