
<form action="action/user_update.php" method="POST" data-ajax="false">
    <input type="hidden" name="userId" value="<?php echo($user->userId); ?>">
    <input type="hidden" name="mail" value="<?php echo($user->systemUser->mail); ?>" />

    <div data-role="fieldcontain">
        <label for="userAccountTel">Phone number: </label>
        <input id="userAccountTel" type="tel" name="tel" value="<?php echo($user->systemUser->tel); ?>" />
    </div>
    <!--
        <div data-role="fieldcontain">
            <label for="userAccountTxt">BIO:</label>
            <input id="userAccountTxt" type="text" name="txt" value="<?php echo($user->systemUser->txt); ?>" />
        </div>
    -->
    <div data-role="fieldcontain">
        <label for="userAccountTitle">Name:</label>
        <input id="userAccountTitle" type="text" name="title" value="<?php echo($user->systemUser->title); ?>" />
    </div>

    <input type="submit" value="Save" />
</form>
