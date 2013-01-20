<?php
$layerManager = new LGLayerManager($user);
$layerColManager = new LGLayerColManager($user);
?>
<div data-role="collapsible">
    <h3>Přidej formulář</h3>

    <form action="action/form_add.php" method="POST"  data-ajax="false">
        <div data-role="fieldcontain">
            <label for="form_add_title">Název: </label>
            <input id="form_add_title" type="text" name="title">
        </div>
        <div data-role="fieldcontain">
            <label for="form_add_txt">Popis: </label>
            <input id="form_add_txt" type="text" name="txt">
        </div>
        <div data-role="fieldcontain">
            <label for="form_add_layerId">Vrstva: </label>
            <select name="layerId" id="form_add_layerId">
                <?php
                foreach ($planManager->getPlans() as $planId) {
                    $plan = new LGPlan($planId);
                    echo('<optgroup label="Plán: ' . $plan->title . '">');
                    foreach ($layerManager->getLayersByPlan($planId) as $layerId) {
                        $layer = new LGLayer($layerId);
                        echo('<option value="' . $layer->layerId . '">' . $layer->title . '</option>');
                    }
                    echo('</optgroup>');
                }
                ?>
            </select>
        </div>
        <div data-role="fieldcontain">
            <label for="form_add_autoupdate">autocomplete (automaticky ukládat do vrstvy na postgis): </label>
            <select name="autoupdate" id="form_add_autoupdate"  data-role="slider">
                <option value="0">Ne</option>
                <option value="1">Ano (momentálně nepodporováno)</option>                
            </select>
        </div>
        <input type="submit" value="Vložit">
    </form>
</div>


<div data-role="collapsible" data-collapsed="false" >
    <h3>Formuláře</h3>
    <div  data-role="collapsible-set">
        <?php
        $formManager = new LGFormManager($user);

        foreach ($planManager->getPlans() as $planId) {
            $plan = new LGPlan($planId);
            foreach ($formManager->getFormsByPlan($planId) as $formId) {
                $form = new LGForm($formId);
                ?>

                <div data-role="collapsible" >
                    <h3><?php echo($plan->title . ' - ' . $form->title); ?></h3>

                    <form action="action/form_update.php" method="POST" data-ajax="false">
                        <input type="hidden" name="layerId" value="<?php echo($form->layerId); ?>">
                        <input type="hidden" name="formId" value="<?php echo($form->formId); ?>">

                        <div data-role="fieldcontain">
                            <label for="form_edit_title">Název: </label>
                            <input id="form_edit_title" type="text" name="title" value="<?php echo($form->title); ?>">
                        </div>
                        <div data-role="fieldcontain">
                            <label for="form_edit_txt">Popis: </label>
                            <input id="form_edit_txt" type="text" name="txt"  value="<?php echo($form->txt); ?>">
                        </div>
                        <div data-role="fieldcontain">
                            <label for="form_edit_layerId">Vrstva: </label>
                            <?php
                            $layer = new LGLayer($form->layerId);
                            ?>
                            <input id="form_edit_layerId" type="text" disabled value="<?php echo($layer->title); ?>">
                        </div>
                        <div data-role="fieldcontain">
                            <label for="form_edit_autoupdate">autocomplete (automaticky ukládat do vrstvy na postgis): </label>
                            <select name="autoupdate" id="form_edit_autoupdate">
                                <option value="0">Ne</option>
                                <option value="1" <?php
                    if ($form->autoupdate) {
                        echo('selected');
                    }
                            ?>>Ano (momentálně nepodporováno)</option>                
                            </select>
                        </div>

                        <input type="submit" value="Upravit">
                        <input type="button" onclick="if(window.confirm('Opravdu smazat?')){document.location='action/form_remove.php?formId=<?php echo($formId); ?>'}" value="smazat">
                    </form>



                    <div data-role="collapsible">
                        <h3>Přidat pole</h3>

                        <form action="action/formItem_add.php" method="POST"  data-ajax="false">
                            <input type="hidden" name="formId" value="<?php echo($formId); ?>">
                            <div data-role="fieldcontain">
                                <label for="formItem_add_title">Title: </label>
                                <input id="formItem_add_title" type="text" name="title">
                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_name">Pole: </label>
                                <select  id="formItem_add_name"  name="name">
                                    <?php
                                    foreach ($layerColManager->getColsByLayerId($form->layerId) as $layerColId) {
                                        $layerCol = new LGLayerCol($layerColId);
                                        ?>
                                        <option value="<?php echo($layerCol->name); ?>"><?php echo($layerCol->title); ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_placeholder">Placeholder </label>
                                <input id="formItem_add_placeholder" type="text" name="placeholder">
                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_type">Type </label>
                                <select id="formItem_add_type" name="type">
                                    <?php
                                    foreach ($formManager->getFormEements() as $formElementId) {
                                        $formElement = new LGFormElement($formElementId);
                                        ?>
                                        <option value="<?php echo($formElement->name); ?>"><?php echo($formElement->title); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_default">Default: </label>
                                <input id="formItem_add_default" type="text" name="default">
                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_required">Název (jedinezný [a-z]): </label>
                                <select data-role="slider" id="formItem_add_required" name="required">
                                    <option value="0">nepoviné</option>
                                    <option value="1">poviné</option>
                                </select>
                            </div>
                            <div data-role="fieldcontain">
                                <label for="formItem_add_options">Volby JSON </label>
                                <textarea id="formItem_add_options"  name="options"></textarea>
                            </div>
                            <input type="submit" value="Vložit">
                        </form>
                    </div>


                    <?php
                    foreach ($form->getFormItems() as $formItemId) {
                        $formItem = new LGFormItem($formItemId);
                        ?>

                        <div data-role="collapsible">
                            <h3><?php echo($formItem->title); ?></h3>
                            <form action="action/formItem_update.php" method="POST"  data-ajax="false">
                                <input type="hidden" name="formItemId" value="<?php echo($formItemId); ?>">
                                <input type="hidden" name="formId" value="<?php echo($formId); ?>">
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_title">Title: </label>
                                    <input id="formItem_edit<?php echo($formItemId); ?>_title" type="text" name="title" value="<?php echo($formItem->title); ?>">
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_name">Pole: </label>
                                    <select  id="formItem_edit<?php echo($formItemId); ?>_name"  name="name">
                                        <?php
                                        foreach ($layerColManager->getColsByLayerId($form->layerId) as $layerColId) {
                                            $layerCol = new LGLayerCol($layerColId);
                                            ?>
                                            <option value="<?php echo($layerCol->name); ?>" <?php if ($formItem->name == $layerCol->name) { ?> selected <?php } ?>><?php echo($layerCol->title); ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_placeholder">Placeholder </label>
                                    <input id="formItem_edit<?php echo($formItemId); ?>_placeholder" type="text" name="placeholder" value="<?php echo($formItem->placeholder); ?>">
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_type">Type </label>
                                    <select id="formItem_edit<?php echo($formItemId); ?>_type" name="type">
                                        <?php
                                        foreach ($formManager->getFormEements() as $formElementId) {
                                            $formElement = new LGFormElement($formElementId);
                                            ?>
                                            <option value="<?php echo($formElement->name); ?>" <?php echo($formElement->name == $formItem->type ? 'selected' : ''); ?>><?php echo($formElement->title); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_default">Default: </label>
                                    <input id="formItem_edit<?php echo($formItemId); ?>_default" type="text" name="default" value="<?php echo($formItem->default); ?>">
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_required">Název (jedinezný [a-z]): </label>
                                    <select data-role="slider" id="formItem_edit<?php echo($formItemId); ?>_required" name="required">
                                        <option value="0">nepoviné</option>
                                        <option value="1"  <?php echo($formItem->required ? 'selected' : ''); ?>>poviné</option>
                                    </select>
                                </div>
                                <div data-role="fieldcontain">
                                    <label for="formItem_edit<?php echo($formItemId); ?>_options">Volby JSON </label>
                                    <textarea id="formItem_edit<?php echo($formItemId); ?>_options"  name="options"><?php echo($formItem->options); ?></textarea>
                                </div>
                                <input type="submit" value="Vložit">
                                <input type="button" onclick="if(window.confirm('Opravdu smazat?')){document.location='action/formItem_remove.php?formId=<?php echo($formId); ?>&formItemId=<?php echo($formItemId); ?>'}" value="Smazat">
                            </form>
                        </div>

                        <?php
                    }
                    ?>




                </div>
            <?php }
        } ?>
    </div>
</div>