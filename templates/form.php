<div class="postingFORM">
            <summary class="threadContent"style="color:#3FFF00;">>Posting</summary>
        <form method="POST" id="POST" action="posting.php">
            <input style="width:50%;"type="text" size=48 name="title" id="title" placeholder="Title character limit: 100"/><br><br>
            <textarea spellcheck="true" required="" rows="10" cols="55" name="message" maxlength="<?php echo $CONFIGURATION['MAX_MESSAGE_LENGTH'] ?>" placeholder="Character limit: <?php echo $CONFIGURATION['MAX_MESSAGE_LENGTH'] ?>"></textarea><br><br>
	    <img style="margin-left: 0.9em;box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" src="captcha.php" alt="CAPTCHA"><br><br>
            <input type="text" id="captcha" name="captcha" placeholder="NO SPACES" required><br>
            <br><input class="buttonPOST" type="submit" id="last_submit" name="last_submit" value="POST">
        </form>
</div>
