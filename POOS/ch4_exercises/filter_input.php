<?php
$filters = array('int'             => 'FILTER_VALIDATE_INT',
				 'boolean'         => 'FILTER_VALIDATE_BOOLEAN',
				 'float'           => 'FILTER_VALIDATE_FLOAT',
				 'validate_url'    => 'FILTER_VALIDATE_URL',
				 'validate_email'  => 'FILTER_VALIDATE_EMAIL',
				 'validate_ip'     => 'FILTER_VALIDATE_IP',
				 'unsafe_raw'      => 'FILTER_UNSAFE_RAW',
				 'string'          => 'FILTER_SANITIZE_STRING',
				 'stripped'        => 'FILTER_SANITIZE_STRIPPED',
				 'encoded'         => 'FILTER_SANITIZE_ENCODED',
				 'special_chars'   => 'FILTER_SANITIZE_SPECIAL_CHARS',
				 'email'           => 'FILTER_SANITIZE_EMAIL',
				 'url'             => 'FILTER_SANITIZE_URL',
				 'number_int'      => 'FILTER_SANITIZE_NUMBER_INT',
				 'number_float'    => 'FILTER_SANITIZE_NUMBER_FLOAT',
				 'magic_quotes'    => 'FILTER_SANITIZE_MAGIC_QUOTES');

if (filter_has_var(INPUT_POST, 'send')) {
	$filtered = filter_input(INPUT_POST, 'var', filter_id($_POST['filter']));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Testing filter constants</title>
<style type="text/css">
<!--
label {
	font-weight: bold;
	display: block;
}
body {
	font-family: Arial, Helvetica, sans-serif;
}
form {
	width: 500px;
	margin-left: 50px;
}
#var {
	width: 400px;
	height: 50px;
}
#filtered {
	margin-left: 50px;
}
#hilite {
color:#900;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="var">Input to be filtered:</label>
    <textarea name="var" cols="50" rows="6" id="var"><?php if (isset($_POST['var'])) echo $_POST['var']; ?>
</textarea>
  </p>
  <p>
    <label for="filter">Filter:</label>
    <select name="filter" id="filter">
      <?php foreach ($filters as $key => $value) {
      echo "<option value='$key'";
	  if (isset($_POST['filter']) && $_POST['filter'] == $key) {
		  echo ' selected="selected"';
	  }
	  echo ">$value</option>\n";
	}
	?>
    </select>
  </p>
  <p>
    <input type="submit" name="send" id="send" value="See filtered result" />
  </p>
</form>
<?php if (isset($filtered)) { ?>
  <p id="filtered"><strong>Filtered input:</strong><span id="hilite">
    <?php var_dump($filtered); ?>
    </span>
  </p>
  <?php } ?>
</body>
</html>
