<div class="postingFORM">
    <details open>
        <summary class="threadTop">Posting</summary>
        <br>
        <form method="POST" id="POST" action="posting.php">
            <textarea spellcheck="true" required="" rows="10" cols="60" name="message" maxlength="<?php echo $CONFIGURATION['MAX_MESSAGE_LENGTH'] ?>" placeholder="<?php echo $CONFIGURATION['MAX_MESSAGE_LENGTH'] ?> character posting limit."></textarea>
            <input type="hidden" name="love_snare" value="57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF" placeholder="love_snare">
            <input type="hidden" name="email" placeholder="email"><br><br>
	    <img style="margin-left: 0.9em;" src="captcha.php" alt="CAPTCHA"><br><br>
            <input type="text" id="captcha" name="captcha" placeholder="HINT: FRUIT" required><br>
            <br><input class="buttonPOST" type="submit" id="submit" name="submit" value="POST">
        </form>
    </details>
</div>
