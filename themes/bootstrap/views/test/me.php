<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading' => 'Sample Usage',
)); ?>
<?php $this->endWidget(); ?>

<div class="page-header">
    <h1 id="overview">Get User Profile</h1>
</div>

<p>
    After you get <code>access_token</code> you could retrieve all user data based on it, in this case I want to retrieve user profile.
</p>

<p>
    Send post request to <code>http://sampleapihost.com/user/me</code> with this params :

    <pre>
        token : YOUR_SAVED_ACCESS_TOKEN
    </pre>
</p>

You'll retrieve the user profile in JSON format

<pre>
    {
        'id' => 1,
        'username' => 'admin',
        'first_name' => 'Jhon',
        'last_name' => 'Doe',
        'email' => 'jhon_doe@email.host',
        'join_date' => '2013-07-25 16:56:42',
    }
</pre>

<div class="clearfix"></div>