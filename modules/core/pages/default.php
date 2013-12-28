<h4>Add new page</h4>
<form method="post" action="<?php echo $action ?>">
    <input type="text" name="Name">
    <input type="text" name="accesslevel" value="everyone">
    <?php generateThemePages("singlepage.html"); ?>
    <input type="submit" value="Add page" name="submitted">
</form>
