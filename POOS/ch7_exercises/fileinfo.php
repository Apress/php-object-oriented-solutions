<pre>
<?php
// Replace the filename in the constructor to examine another file or directory
$file = new SplFileInfo('anonymous_iterator_01.php');
$info['atime']      = $file->getATime();
$info['ctime']      = $file->getCTime();
$info['filename']   = $file->getFilename();
$info['group']      = $file->getGroup();
$info['inode']      = $file->getInode();
$info['mtime']      = $file->getMTime();
$info['owner']      = $file->getOwner();
$info['path']       = $file->getPath();
$info['pathname']   = $file->getPathname();
// The following line converts the base-10 number returned by getPerms() to an octal number
$info['perms']      = substr(sprintf('%o' ,$file->getPerms()), -4);
$info['realpath']   = $file->getRealPath();
$info['size']       = $file->getSize();
$info['type']       = $file->getType();
$info['dir']        = $file->isDir();
$info['exec']       = $file->isExecutable();
$info['isfile']     = $file->isFile();
$info['islink']     = $file->isLink();
// The following line gets the link target if the object is a link; otherwise, false
$info['linkTarget'] = $file->isLink() ? $file->getLinkTarget() : false;
$info['readable']   = $file->isReadable();
$info['writable']   = $file->isWritable();
var_dump($info);
?>
</pre>