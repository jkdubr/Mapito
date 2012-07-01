<?php
$layerStyleManager = new LGLayerStyleManager($user);
?>
<div data-role="collapsible">
    <h3>New SLD style</h3>

    <form action="action/layerStyle_add.php" method="POST" enctype="multipart/form-data"  data-ajax="false">
        <div data-role="fieldcontain">
            <label for="layerStyle_add_title">Name: </label>
            <input id="layerStyle_add_title" type="text" name="title">
        </div>
        <div data-role="fieldcontain">
            <label for="layerStyle_add_txt">Description: </label>
            <input id="layerStyle_add_txt" type="text" name="txt">
        </div>
        <div data-role="fieldcontain">
            <label for="layerStyle_add_content">SLD content: </label>
            <textarea id="layerStyle_add_content" type="text" name="content"></textarea>
        </div>
        <div data-role="fieldcontain">
            <label for="layerStyle_add_public">Is public: </label>
            <select id="layerStyle_add_public"  name="public">
                <option value="0">private</option>    
                <option value="1">public</option>    
            </select>
        </div>
        <div data-role="fieldcontain">
            <label for="layerStyle_add_ico">Style icon: </label>
            <input type="file" name="file_ico" accept="image/*" id="layerStyle_add_ico">
        </div>


        <input type="submit" value="Submit">
    </form>
</div>




<div data-role="collapsible" data-collapsed="false" >
    <h3>My SLD styles</h3>
    <div  data-role="collapsible-set">
        <?php
        foreach ($layerStyleManager->getStylesByUser() as $layerStyleId) {
            $layerStyle = new LGLayerStyle($layerStyleId);
            ?>

            <div data-role="collapsible" >
                <h3><img src="../img/style/<?php echo($layerStyle->layerStyleId)?>.png"> <?php echo($layerStyle->title); ?></h3>





                <form action="action/layerStyle_update.php" method="POST"  enctype="multipart/form-data"  data-ajax="false">
                    <input type="hidden" name="layerStyleId" value="<?php echo($layerStyle->layerStyleId); ?>">

                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_title">Name: </label>
                        <input id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_title" type="text" name="title" value="<?php echo($layerStyle->title); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_txt">Descriptin: </label>
                        <input id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_txt" type="text" name="txt" value="<?php echo($layerStyle->txt); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_content">SLD content: </label>
                        <textarea id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_content" type="text" name="content"><?php echo($layerStyle->content); ?></textarea>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="layerStyle_add_public">Is public: </label>
                        <select id="layerStyle_add_public"  name="public">
                            <option value="0">private</option>    
                            <option value="1" <?php echo($layerStyle->public ? 'selected' : ''); ?>>public</option>    
                        </select>
                    </div>

                    <div data-role="fieldcontain">
                        <label for="layerStyle_add_ico">Modify icon: </label>
                        <input type="file" name="fileIco" accept="image/*" id="layerStyle_add_ico">
                    </div>
                    <input type="submit" value="Save">
                    <input type="button" value="Remove" onclick="if(window.confirm('Remove style?')){document.location='action/layerStyle_remove.php?layerStyleId=<?php echo($layerStyle->layerStyleId); ?>'}">
                </form>
            </div>
        <?php } ?>
    </div>
</div>




<div data-role="collapsible" data-collapsed="false" >
    <h3>Public SLD styles</h3>
    <div  data-role="collapsible-set">
        <?php
        foreach ($layerStyleManager->getStylesPublic() as $layerStyleId) {
            $layerStyle = new LGLayerStyle($layerStyleId);
            ?>

            <div data-role="collapsible" >
                <h3><img src="../img/style/<?php echo($layerStyle->layerStyleId)?>.png"> <?php echo($layerStyle->title); ?></h3>





                <form action="action/layerStyle_add.php" method="POST"  data-ajax="false">
                    <input type="hidden" name="parentPublicLayer" value="<?php echo($layerStyle->layerStyleId); ?>">
                    <input type="hidden" name="public" value="0">
                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_title">Name: </label>
                        <input id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_title" type="text" name="title" value="<?php echo($layerStyle->title); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_txt">Description: </label>
                        <input id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_txt" type="text" name="txt" value="<?php echo($layerStyle->txt); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_content">SLD content: </label>
                        <textarea id="layerStyle<?php echo($layerStyle->layerStyleId); ?>_edit_content" type="text" name="content"><?php echo($layerStyle->content); ?></textarea>
                    </div>

                    <input type="submit" value="Use this SLD style">
                </form>
            </div>
        <?php } ?>
    </div>
</div>