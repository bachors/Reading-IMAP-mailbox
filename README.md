# Reading-IMAP-mailbox
Reading emails from a IMAP mailbox - PHP

<h2>Setting:</h2>
<pre>// Multiple email account
$emails = array(
    array(
        'no'        =&gt; '1',
        'label'     =&gt; 'Inbox Email 1',
        'host'         =&gt; '{mail.domain.com:143/notls}INBOX',
        'username'     =&gt; 'mail1@domain.com',
        'password'     =&gt; 'xxxxxxxxxx'
    ),
    array(
        'no'        =&gt; '2',
        'label'     =&gt; 'Inbox Email 2',
        'host'         =&gt; '{mail.domain.net:143/notls}INBOX',
        'username'     =&gt; 'mail2@domain.net',
        'password'     =&gt; 'xxxxxxxxxx'
    )
    // bla bla bla ...
);</pre>
<a href="http://ibacor.com/inbox.php" target="_BLANK">DEMO</a>
