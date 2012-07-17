                
<div  data-role="collapsible-set">
    <div data-role="collapsible">

        <h3>New user</h3>
        <form action="action/user_add.php" method="POST"  data-ajax="false">
            <div data-role="fieldcontain">

                <label for="users_edit_title">Name:</label>
                <input id="users_edit_title" type="text" name="title">
            </div>
            <div data-role="fieldcontain">
                <label for="users_edit_mail">Email:</label>
                <input id="users_edit_mail" type="email" name="mail">
            </div>
            <div data-role="fieldcontain">
                <label for="users_edit_password">Password:</label>
                <input id="users_edit_password" type="text" name="password">
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>

    <?php
    $userManager = new LGUserManager($user);
    foreach ($userManager->getUsers() as $systemUserId) {
        $systemUser = new LGSystemUser($systemUserId);
        ?>

        <div data-role="collapsible">
            <h3><?php echo($systemUser->title); ?></h3>

            <form action="action/user_update.php" method="POST"  data-ajax="false">  
                <input type="hidden" name="userId" value="<?php echo($systemUser->userId); ?>">
                <div data-role="fieldcontain">
                    <label for="users_edit_title<?php echo($systemUser->userId); ?>">Name:</label>
                    <input id="users_edit_title<?php echo($systemUser->userId); ?>" type="text" name="title" value="<?php echo($systemUser->title); ?>">
                </div>
                <div data-role="fieldcontain">
                    <label for="users_edit_mail<?php echo($systemUser->userId); ?>">Email:</label>
                    <input id="users_edit_mail<?php echo($systemUser->userId); ?>" type="email" name="mail" value="<?php echo($systemUser->mail); ?>">
                </div>
                <div data-role="fieldcontain">
                    <label for="users_edit_tel<?php echo($systemUser->userId); ?>">Phone number:</label>
                    <input id="users_edit_tel<?php echo($systemUser->userId); ?>" type="tel" name="tel" value="<?php echo($systemUser->tel); ?>">
                </div>
                <div data-role="fieldcontain">
                    <label for="users_edit_txt<?php echo($systemUser->userId); ?>">BIO:</label>
                    <input id="users_edit_txt<?php echo($systemUser->userId); ?>" type="text" name="txt" value="<?php echo($systemUser->txt); ?>">
                </div>

                <?php
                foreach ($planManager->getPlans() as $planId) {
                    $plan = new LGPlan($planId);
                    $privilege = new LGPlanPrivilege($systemUser->userId, $planId);
                    ?>
                    <div data-role="fieldcontain">
                        <label for="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_privilege<?php echo($plan->planId); ?>"><?php echo($plan->title); ?> - privilege:</label>

                        <input id="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_privilege<?php echo($plan->planId); ?>" type="hidden" name="planPrivilege_planId[]" value="<?php echo($plan->planId); ?>">
                        <select id="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_privilege<?php echo($plan->planId); ?>" name="planPrivilege_privilege[]">
                            <option value="0" <?php echo($privilege->privilege == 0 ? "selected" : ""); ?>>none</option>
                            <option value="1" <?php echo($privilege->privilege == 1 ? "selected" : ""); ?>>viewer</option>
                            <option value="2" <?php echo($privilege->privilege == 2 ? "selected" : ""); ?>>editation</option>
                            <option value="3" <?php echo($privilege->privilege == 3 ? "selected" : ""); ?>>admin</option>
                        </select>   
                    </div>
                    <!--    <div data-role="fieldcontain">
                            <label for="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_dateFrom<?php echo($plan->planId); ?>">from date:</label>

                            <input id="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_dateFrom<?php echo($plan->planId); ?>" type="date" name="planPrivilege_dateFrom[]" value="<?php echo($privilege->dateFrom); ?>">

                        </div>
                        <div data-role="fieldcontain">
                            <label for="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_dateTo<?php echo($plan->planId); ?>">to date:</label>
                            <input id="users_edit<?php echo($systemUser->userId); ?>_planPrivilege_dateTo<?php echo($plan->planId); ?>" type="date" name="planPrivilege_dateTo[]" value="<?php echo($privilege->dateTo); ?>">
                        </div>
                    -->
                    <?php
                }
                ?>


                <input type="submit" value="Update">
                <input type="button" value="Remove user" data-theme="a"  onclick="if(window.confirm('Remove user?')){document.location='action/user_remove.php?userId=<?php echo($systemUser->userId); ?>'}">
            </form>
        </div>
    <?php }
    ?>
</div>