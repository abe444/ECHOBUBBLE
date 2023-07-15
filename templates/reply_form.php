<div class="replyingFORM">
<details open>
<summary class="threadTop">[Mode]: Replying</summary><br>
<form method="POST" id="POST" action="replying.php">
    <textarea spellcheck="true" required="" rows="10" cols="60" name="message" maxlength="3000" placeholder="2500 character limit."></textarea>
    <input type="hidden" name="love_snare" value="57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF" placeholder="love_snare">
    <input type="hidden" name="email" placeholder="email">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET['id'])); ?>" readonly="true">
    <br><input class="buttonPOST" type="submit" id="last_submit" name="last_submit" value="POST">
</form>
</details>
</div>