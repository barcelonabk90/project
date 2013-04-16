<html>
<body>

<?php
    echo "Upload CSV file";
?>
<form action="upcsv" method="post" enctype="multipart/form-data">
<label for="file">  File name: </label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>