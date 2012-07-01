
<div data-role="collapsible">
    <h3>New plan</h3>

    <form action="action/plan_add.php" method="POST"  enctype="multipart/form-data" data-ajax="false">
        <div data-role="fieldcontain">
            <label for="plan_add_name">Unique name ([a-z]): </label>
            <input id="plan_add_name" type="text" name="name" >
        </div>
        <div data-role="fieldcontain">
            <label for="plan_add_title">Title: </label>
            <input id="plan_add_title" type="text" name="title">
        </div>
        <div data-role="fieldcontain">
            <label for="plan_add_txt">About map: </label>
            <textarea id="plan_add_txt" name="txt"></textarea>
        </div>
        <div data-role="fieldcontain">
            <label for="plan_edit0_mapCenterLat">Map center latitude (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=0','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>): </label>
            <input id="plan_edit0_mapCenterLat" type="text" name="mapCenterLat">
        </div>
        <div data-role="fieldcontain">
            <label for="plan_edit0_mapCenterLon">Map center longitude (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=0','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>)>map</a>): </label>
            <input id="plan_edit0_mapCenterLon" type="text" name="mapCenterLon">
        </div>
        <div data-role="fieldcontain">
            <label for="plan_edit0_mapZoom">Map zoom (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=0','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>)>map</a>): </label>
            <input id="plan_edit0_mapZoom" type="range" value="17" min="1" max="20" name="mapZoom">
        </div>
        <div data-role="fieldcontain">
            <label for="plan_add_private">Is public: </label>
            <select id="plan_add_private" name="private">
                <option value="0">private</option>
                <option value="1" selected>public</option>
            </select>
        </div>
        <div data-role="fieldcontain">
            <label for="plan_add_fileSplashscreen">Splashscreen: </label>
            <input id="plan_add_fileSplashscreen" type="file" accept="image/*" name="fileSplashscreen">
        </div>
        <input type="submit" value="Vložit">
    </form>
</div>


<div data-role="collapsible" data-collapsed="false" >
    <h3>Plány</h3>
    <div  data-role="collapsible-set">
        <?php
        foreach ($planManager->getPlans() as $planId) {
            $plan = new LGPlan($planId);
            ?>

            <div data-role="collapsible" >
                <h3><?php echo($plan->title); ?></h3>

                <form action="action/plan_update.php" method="POST"  enctype="multipart/form-data" data-ajax="false">
                    <input type="hidden" name="planId" value="<?php echo($planId); ?>">
                    <div data-role="fieldcontain">
                        <a href="<?php echo($LGSettings->map_url.$plan->name); ?>" target="_blank"  rel="external" data-ajax="false" >To map</a>
                    </div>

                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_name">Unique name: </label>
                        <input id="plan_edit<?php echo($planId); ?>_name" type="text" name="name" value="<?php echo($plan->name); ?>" readonly>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_title">Title: </label>
                        <input id="plan_edit<?php echo($planId); ?>_title" type="text" name="title" value="<?php echo($plan->title); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_mapCenterLat">Map center latitude (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=<?php echo($planId)?>','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>): </label>
                        <input id="plan_edit<?php echo($planId); ?>_mapCenterLat" type="text" name="mapCenterLat" value="<?php echo($plan->mapCenterLat); ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_mapCenterLon">Map center longitude (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=<?php echo($planId)?>','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>): </label>
                        <input id="plan_edit<?php echo($planId); ?>_mapCenterLon" type="text" name="mapCenterLon" value="<?php echo($plan->mapCenterLon); ?>">
                    </div>

                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_mapZoom">Zoom (<a  onClick="window.open('page/plan_edit/plan_edit_centerMap/map.php?planId=<?php echo($planId)?>','','toolbar=1,resizable=1,scrollbars=yes,height=600px,width=600px');"  data-ajax="false">map</a>): </label>
                        <input id="plan_edit<?php echo($planId); ?>_mapZoom" type="range" value="<?php echo($plan->mapZoom); ?>" min="1" max="20" name="mapZoom">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_txt">About map: </label>
                        <textarea id="plan_edit<?php echo($planId); ?>_txt" name="txt"><?php echo($plan->txt); ?></textarea>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_private">Is public: </label>
                        <select id="plan_edit<?php echo($planId); ?>_private" name="private"  data-role="slider">
                            <option value="1">private</option>
                            <option value="0" <?php echo($plan->private ? "" : "selected"); ?>>public</option>
                        </select>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="plan_edit<?php echo($planId); ?>_fileSplashscreen">Splashscreen: </label>
                        <input id="plan_edit<?php echo($planId); ?>_fileSplashscreen" type="file" accept="image/*" name="fileSplashscreen">
                    </div>
                    <?php if ($plan->isSplashScreen()) { ?>
                        <div data-role="fieldcontain">
                            <label for="plan_edit<?php echo($planId); ?>_splashscreenRemove">          
                                <a href="../<?php echo($plan->name); ?>/splashscreen.png" target="_blank">Display splashscreen</a>
                            </label>
                            <select id="plan_edit<?php echo($planId); ?>_splashscreenRemove" name="splashscreenRemove" data-role="slider">
                                <option value="1">Remove splashscreen</option>
                                <option selected>Display splashscreen</option>
                            </select>
                        </div>
                    <?php } ?>


                    <input type="submit" value="Update"> <input type="button" onclick="if(window.confirm('Remove plan?')){document.location='action/plan_remove.php?planId=<?php echo($planId); ?>'}" value="Remove">


                </form>
            </div>
        <?php } ?>
    </div>
</div>