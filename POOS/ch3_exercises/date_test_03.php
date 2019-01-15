<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DateTime constants</title>
<style type="text/css">
td {padding: 2px 10px;}
</style>
</head>

<body>
<table>
  <?php $date = new DateTime(); ?>
  <tr>
    <th scope="col">Constant</th>
    <th scope="col">Output</th>
  </tr>
  <tr>
    <td>ATOM</td>
    <td><?php echo $date->format(DateTime::ATOM); ?></td>
  </tr>
  <tr>
    <td>COOKIE</td>
    <td><?php echo $date->format(DateTime::COOKIE); ?></td>
  </tr>
  <tr>
    <td>ISO8601</td>
    <td><?php echo $date->format(DateTime::ISO8601); ?></td>
  </tr>
  <tr>
    <td>RFC822</td>
    <td><?php echo $date->format(DateTime::RFC822); ?></td>
  </tr>
  <tr>
    <td>RFC850</td>
    <td><?php echo $date->format(DateTime::RFC850); ?></td>
  </tr>
  <tr>
    <td>RFC1036</td>
    <td><?php echo $date->format(DateTime::RFC1036); ?></td>
  </tr>
  <tr>
    <td>RFC1123</td>
    <td><?php echo $date->format(DateTime::RFC1123); ?></td>
  </tr>
  <tr>
    <td>RFC2822</td>
    <td><?php echo $date->format(DateTime::RFC2822); ?></td>
  </tr>
  <tr>
    <td>RFC3339</td>
    <td><?php echo $date->format(DateTime::RFC3339); ?></td>
  </tr>
  <tr>
    <td>RSS</td>
    <td><?php echo $date->format(DateTime::RSS); ?></td>
  </tr>
  <tr>
    <td>W3C</td>
    <td><?php echo $date->format(DateTime::W3C); ?></td>
  </tr>
</table>
</body>
</html>
