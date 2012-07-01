

<div data-role="collapsible" data-collapsed="false" >
    <h3>My maps</h3>
    <?php
    $layerManager = new LGLayerManager($user);
    ?>



    <div  data-role="collapsible-set">
        <?php
        foreach ($planManager->getPlans() as $planId) {
            //  $layerManager->updateLayersFromPostgis($planId);
            $plan = new LGPlan($planId);

            $planPrivilege = $user->getPrivilegeForPlan($planId);
            if ($planPrivilege < 2)
                continue;
            ?>
            <div data-role="collapsible"  <?php if ($planId == (int) $_GET["planId"]) { ?> data-collapsed="false" <?php } ?>>

                <h3><?php echo($plan->title); ?></h3>
                <a data-ajax="false" href="action/layer_updateLayersFromPostgis.php?planId=<?php echo($planId); ?>">Update layers from PostGIS</a>
                <div data-role="collapsible"  >
                    <h3>Folders</h3>
                    <div  data-role="collapsible-set">

                        <div data-role="collapsible">
                            <h3>New folder</h3>
                            <form action="action/layerFolder_add.php" method="POST"  data-ajax="false">
                                <div data-role="fieldcontain">
                                    <label>Name:</label>
                                    <input type="text" name="title">
                                </div>

                                <input type="hidden" name="planId" value="<?php echo($plan->planId) ?>">
                                <input type="submit" value="Submit">
                            </form>
                        </div>    



                        <?php
                        foreach ($layerManager->getLayerFoldersByPlan($plan->planId) as $layerFolderId) {
                            $layerFolder = new LGLayerFolder($layerFolderId);
                            ?>
                            <div data-role="collapsible">
                                <h3><?php echo($layerFolder->title); ?></h3>
                                <form action="action/layerFolder_update.php" method="POST"  data-ajax="false">
                                    <input type="hidden" name="layerFolderId" value="<?php echo($layerFolder->layerFolderId); ?>">
                                    <input type="hidden" name="planId" value="<?php echo($layerFolder->planId); ?>">
                                    <div data-role="fieldcontain">
                                        <label for="layerFolders<?php echo($layerFolderId); ?>_edit_title">Name:</label>
                                        <input required id="layerFolders<?php echo($layerFolderId); ?>_edit_title" type="text" name="title" value="<?php echo($layerFolder->title); ?>">
                                    </div>
                                    <div data-role="fieldcontain">
                                        <label for="layerFolders<?php echo($layerFolderId); ?>_edit_txt">Description:</label>
                                        <input id="layerFolders<?php echo($layerFolderId); ?>_edit_txt" type="text" name="txt" value="<?php echo($layerFolder->txt); ?>">
                                    </div>
                                    <input type="submit" value="Submit">
                                    <?php if (!$layerFolder->basic) { ?>
                                        <input type="button" value="Remove" onclick="if(window.confirm('Remove?')){document.location='action/layerFolder_remove.php?layerFolderId=<?php echo($layerFolder->layerFolderId); ?>'}">
                                    <?php } ?>
                                </form>
                            </div>
                        <?php } ?>


                    </div>
                </div>



                <div data-role="collapsible" data-collapsed="false" >
                    <h3>Layers <?php
                    if ($layerManager->getNumberOfNewLayersByPlan($planId)) {
                        echo('<span style="float: right">' . $layerManager->getNumberOfNewLayersByPlan($planId) . '</span>');
                    }
                        ?></h3>
                    <div  data-role="collapsible-set">


                        <div data-role="collapsible">
                            <h3>Add public layer to map</h3>
                            <form action="action/layerPublic_add.php" method="POST"  data-ajax="false">
                                <div data-role="fieldcontain">
                                    <label for="layerPublic_add_layerId">Layer:</label>

                                    <select id="layerPublic_add_layerId" name="layerId">
                                        <?php
                                        foreach ($layerManager->getLayersPublic() as $layerId) {
                                            $layer = new LGLayer($layerId);
                                            ?>
                                            <option value="<?php echo($layerId); ?>" title="<?php echo($layer->url . $layer->namespace . $layer->name); ?>"><?php echo($layer->title); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <input type="hidden" name="planId" value="<?php echo($planId) ?>">
                                <input type="submit" value="Add to map">
                            </form>
                        </div> 
                        <div data-role="collapsible">
                            <h3>Add remote WMS</h3>
                            <form action="action/layer_add.php" method="POST"  data-ajax="false">


                                <div data-role="fieldcontain">
                                    <label for="layers_edit_title">Name:</label>
                                    <input id="layers_edit_title" type="text" name="title" >
                                </div>

                                <div data-role="fieldcontain">
                                    <label for="layers_edit_name">Layer name:</label>
                                    <input id="layers_edit_name"  type="text" name="name" >
                                </div>

                                <div data-role="fieldcontain">
                                    <label for="layers_edit_txt">Description:</label>
                                    <input id="layers_edit_txt" type="text" name="txt" >
                                </div>

                                <div data-role="fieldcontain">
                                    <label for="layers_edit_url">WMS URL:</label>
                                    <input id="layers_edit_url" type="url" name="url" >
                                </div>

                                <div data-role="fieldcontain">
                                    <label for="layers_edit_namespace">Namespace:</label>
                                    <input id="layers_edit_namespace"  type="text" name="namespace" >
                                </div>



                                <input type="hidden" name="planId" value="<?php echo($planId) ?>">
                                <input type="submit" value="Add to map">
                            </form>
                        </div>  

                    </div>
                    <div  data-role="collapsible-set">





                        <!--
                                                <div data-role="collapsible">
                                                    <h3>Přidat RASTR</h3>
                                                    <form action="action/layer_add.php" method="POST"  data-ajax="false">
                                                        <input type="hidden" name="url" value="http://ligeo.mostar.cz/admin/api/proxyWms.php?">
                                                        <input type="hidden" name="namespace" value="ligeo_<?php echo($plan->name); ?>">
                                                        <input type="hidden" name="type" value="RASTR"> 
                                                        <div data-role="fieldcontain">
                                                            <label>Název</label>
                                                            <input type="text" name="name" placeholder="<?php echo($plan->name) ?>_rastr">.tif
                                                        </div>
                                                        <input type="hidden" name="planId" value="<?php echo($plan->planId) ?>">
                                                        <input type="submit" value="Vložit">
                                                    </form>
                                                </div>
                        -->






                        <?php
                        $rank = 0;
                        foreach ($layerManager->getLayersByPlan($plan->planId) as $layerId) {

                            $layer = new LGLayer($layerId);
                            if ($planPrivilege < 3 && $layer->isLocked)
                                continue;

                            $rank++;
                            ?>

                            <div data-role="collapsible" id="layer_edit_<?php echo($layer->layerId); ?>"  <?php if ($layerId == (int) $_GET["layerId"]) { ?> data-collapsed="false" <?php } ?>>
                                <h3 onClick='$.mobile.changePage( "index.php?page=layers_edit&layerId=<?php echo($layerId) ?>&planId=<?php echo($planId) ?>#layer_edit_<?php echo($layer->layerId); ?>" );'><?php echo($layer->title); ?> <?php if ($layer->new) { ?><span style="float: right" class="ul-li-count">new</span><?php } ?></h3>
                                <?php if ($layerId == (int) $_GET["layerId"]) { ?>
                                    <form action="action/layer_update.php" method="POST" data-ajax="false"  enctype="multipart/form-data">
                                        <input type="hidden" name="layerId" value="<?php echo($layer->layerId); ?>">
                                        <input type="hidden" name="planId" value="<?php echo($layer->planId); ?>">



                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_title">Name:</label>
                                            <input id="layers_edit_title" type="text" name="title" value="<?php echo($layer->title); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_name">Unique folder name:</label>
                                            <input id="layers_edit_name" readonly type="text" name="name" value="<?php echo($layer->name); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_txt">Description:</label>
                                            <input id="layers_edit_txt" type="text" name="txt" value="<?php echo($layer->txt); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_url">WMS URL:</label>
                                            <input id="layers_edit_url" type="url" name="url" readonly value="<?php echo($layer->url); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_namespace">Namespace:</label>
                                            <input id="layers_edit_namespace" readonly type="text" name="namespace" value="<?php echo($layer->namespace); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_format">Format:</label>
                                            <input id="layers_edit_format" readonly type="text" name="format" value="<?php echo($layer->format); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_opacity">Opacity (0=hidden):</label>
                                            <input id="layers_edit_opacity" type="range" name="opacity"  value="<?php echo($layer->opacity); ?>" min="1" max="10">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_transparent">Transparent:</label>
                                            <select name="transparent" id="layers_edit_transparent" data-role="slider">
                                                <option value="0" >no</option>
                                                <option value="1" <?php
                        if ($layer->transparent) {
                            echo('selected');
                        }
                                    ?>>yes</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_palete">Palete:</label>
                                            <input id="layers_edit_palete" type="text" readonly name="palete" value="<?php echo($layer->palete); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_type">Type:</label>
                                            <input id="layers_edit_type" type="text" readonly name="type" value="<?php echo($layer->type); ?>">
                                        </div>


                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_fileLegend">Map legend (for RASTR layer):</label>

                                            <input id="layers_edit_fileLegend" type="file" name="fileLegend">
                                            <a href='../img/legend/<?php echo($layerId); ?>.png' target='_blank'>Legenda</a>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_layerFolderId">Folder:</label>
                                            <select id="layers_edit_layerFolderId" name="layerFolderId">
                                                <?php
                                                foreach ($layerManager->getLayerFoldersByPlan($plan->planId) as $layerFolderId) {
                                                    $layerFolder = new LGLayerFolder($layerFolderId);
                                                    ?>
                                                    <option value="<?php echo($layerFolder->layerFolderId); ?>" <?php echo($layerFolder->layerFolderId == $layer->layerFolderId ? "selected" : ""); ?> title="<?php echo($layerFolder->txt); ?>"><?php echo($layerFolder->title); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_rank">Rank:</label>
                                            <input id="layers_edit_rank" type="number" name="rank" min="0" max="100" value="<?php echo($rank); ?>">
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_private">Display layer for:</label>
                                            <select name="private" id="layers_edit_private" data-role="slider">
                                                <option value="0" >all</option>
                                                <option value="1" <?php
                                    if ($layer->private) {
                                        echo('selected');
                                    }
                                                ?>>login users</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_queryable">Get feature info:</label>

                                            <select name="queryable" id="layers_edit_queryable" data-role="slider">
                                                <option value="0" >disabled</option>
                                                <option value="1" <?php
                                            if ($layer->queryable) {
                                                echo('selected');
                                            }
                                                ?>>enabled</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_printable">Print layer:</label>

                                            <select name="printable" id="layers_edit_printable" data-role="slider">
                                                <option value="0" >no</option>
                                                <option value="1" <?php
                                            if ($layer->printable) {
                                                echo('selected');
                                            }
                                                ?>>yes</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_visibility" title="Mnoho vrstev zpomaluje načítání mapy">Onload map:</label>
                                            <select name="visibility" id="layers_edit_visibility" data-role="slider">
                                                <option value="0" >hide layer</option>
                                                <option value="1" <?php
                                            if ($layer->visibility) {
                                                echo('selected');
                                            }
                                                ?>>show layer</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_isInLegend">Generate legend for layer:</label>
                                            <select name="isInLegend" id="layers_edit_isInLegend" data-role="slider">
                                                <option value="0">disabled</option>
                                                <option value="1" <?php
                                            if ($layer->isInLegend) {
                                                echo('selected');
                                            }
                                                ?>>enabled</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_isActive">Show layer on map:</label>
                                            <select name="isActive" id="layers_edit_isActive" data-role="slider">
                                                <option value="0">disabled</option>
                                                <option value="1" <?php
                                            if ($layer->isActive) {
                                                echo('selected');
                                            }
                                                ?>>enabled</option>
                                            </select>
                                        </div>

                                        <div data-role="fieldcontain">
                                            <label for="layers_edit_isLocked" title="Vrstvu nebude možné editovat">Lock for editing:</label>
                                            <select name="isLocked" id="layers_edit_isLocked" data-role="slider">
                                                <option value="0">unlock</option>
                                                <option value="1" <?php
                                            if ($layer->isLocked) {
                                                echo('selected');
                                            }
                                                ?>>lock</option>
                                            </select>
                                        </div>

                                        <?php
                                        $layerStyle = new LGLayerStyle($layer->layerStyleId);
                                        ?>
                                        <div data-role="collapsible">
                                            <h3>Select SLD style</h3>
                                            <div  data-role="fieldcontain">
                                                <fieldset data-role="controlgroup">

                                                    <?php
                                                    //<legend>Choose a pet:</legend>
                                                    $layerStyleManager = new LGLayerStyleManager($user);
                                                    foreach ($layerStyleManager->getStylesByUser() as $layerStyleId) {
                                                        $layerStyle = new LGLayerStyle($layerStyleId);
                                                        ?>
                                                        <input type="radio" name="layerStyleId" id="radio-choice-<?php echo($layerStyle->layerStyleId); ?>" value="<?php echo($layerStyle->layerStyleId); ?>" <?php echo($layerStyle->layerStyleId == $layer->layerStyleId ? "checked" : ""); ?>/>
                                                        <label for="radio-choice-<?php echo($layerStyle->layerStyleId); ?>"><?php echo($layerStyle->title); ?></label>

                                                    <?php } ?>
                                                </fieldset>
                                            </div>
                                        </div>


                                        <a href="index.php?page=layerStyle_edit&layerId=<?php echo($layerId); ?>">Update style</a>
                                        <input type="submit" value="<?php if ($layer->new) { ?>Add layer<?php } else { ?>Save<?php } ?>" data-theme="">

                                        <?php if ($layer->type == "RASTR") { ?>
                                            <input type="button" value="Remove layer" data-theme="a" onclick="if(window.confirm('Remove layer?')){document.location='action/layer_remove.php?layerId=<?php echo($layer->layerId); ?>'}"> 
                                        <?php } ?>
                                    </form>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>

                </div>

            </div>

        <?php } ?>
    </div> 
</div>