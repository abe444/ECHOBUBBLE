<center><a style="font-weight:bold;text-shadow: 1px 1px 10px black;" href="#top">Top</a></center>
<div class="replyingFORM">
<summary class="threadContent"style="color:#3FFF00;">>Replying</summary>
<form method="POST" id="POST" action="replying.php">
    <textarea spellcheck="true" required="" rows="10" cols="55" name="message" maxlength="3000" placeholder="2500 character limit."></textarea>
    <input type="hidden" name="love_snare" value="57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF" placeholder="love_snare">
    <input type="hidden" name="email" placeholder="email"><br><br>
	    <img style="margin-left: 0.9em;box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" src="captcha.php" alt="CAPTCHA"><br><br>
            <input type="text" id="captcha" name="captcha" placeholder="CAPTCHA" required><br>
    <input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET['id'])); ?>" readonly="true">
    <br><input class="buttonPOST" type="submit" id="last_submit" name="last_submit" value="POST">
</form>
</div>
