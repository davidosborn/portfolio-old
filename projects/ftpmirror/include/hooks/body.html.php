<p>
	This is a small Python program to mirror a local directory tree to a server using FTP.
	It is useful if your server doesn't support <a href="http://rsync.samba.org/">rsync</a> or SSH, but if either of those are available you should use them instead.
</p>

<h1>Features</h1>
<ul>
	<li>The ability to exclude specific files and directories.</li>
	<li>Checking timestamp and file size to only upload changed files.</li>
	<li>Optional removal of orphaned files from the server.</li>
</ul>

<h1>Example</h1>
<p>
	This example will upload the current directory to the server, excluding any files that match the regular expression.
	It will also delete any files that it finds on the server that should not be there, including any that have been excluded.
</p>

<pre class="brush: bash">
ftpmirror.py --clean --exclude
	'.*~|.git(|ignore)|include/cache|upload.sh'
	'ftp.dreamhost.com' 'davidcosborn' 'password'
	'/davidcosborn.com/portfolio'
</pre>

<h1>Downloads</h1>
<p>
	You can download the source code from the <a href="https://github.com/davidcosborn/ftpmirror/"><?php echo SITE_CODE_HOST?> repository</a>
	under the <a href="<?php echo SITE_CODE_LICENSE_LINK?>"><?php echo SITE_CODE_LICENSE?></a>.
</p>
