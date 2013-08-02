<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading' => 'Login authorization Summary',
)); ?>
<?php $this->endWidget(); ?>

<div class="page-header">
    <h1 id="overview">Summary</h1>
</div>
<p>
    Redirect user to <code>http://sampleapihost.com/auth/authorize?client_id=YOUR_CLIENT_ID&response_type=code</code>
</p>

<p>
    If the user authorizes your application, the user's browser will be redirected (via HTTP 302) to the redirection URI
    (the one on database record in clients table) with an access token passed in a query string,
    e.g. <code>http://your-domain.com/path?code=AUTHORIZATION_CODE</code>.
</p>

<p>
    With the authorization code in hand, retrieve the access token (you'll need this token to make API calls)
    by issuing a POST request to <code>http://sampleapihost.com/oauth/access_token</code>.
</p>

Params:

<pre>
    client_id : Your Application ID
    client_secret : Your Application secret
    code : Authorization code
    grant_type : authorization_code
    redirect_uri : YOUR_REDIRECT_URI (optional)
</pre>

You'll retrieve the access token in JSON format.

<pre>
    {
        "access_token": "YOUR_ACCESS_TOKEN",
        "expires_in": time,
        "scope": null,
        "refresh_token": "YOUR_REFRESH_TOKEN"
    }
</pre>

<p>
    With the access token in hand, your application is ready to make API calls. Below is sample button :
</p>

<?php if (empty(Yii::app()->session['rest_api'])) : ?>
    <a href="<?php echo Yii::app()->rest->api_host; ?>auth/authorize?client_id=<?php echo Yii::app()->rest->app_id; ?>&response_type=code" class="btn btn-primary">Request Access Login</a>
<?php else : ?>
    Success
<?php endif; ?>

<div class="clearfix"></div>