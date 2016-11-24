<div class="col-sm-6 col-md-6 col-lg-6 main">
    <div class="row">
        <div class="col-sm-12 col-md12 col-lg-12">
            <h2 style="background-color:#0CAAD8;vertical-align: bottom;text-align: center;color: white;padding: 10px;">
                <b> Early Warning System (EWS) </b>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md12 col-lg-12"> 
            You are receiving this email because we received a password reset request for your account.
            <br><br>
            <p style="text-align: center">
                <a href="{{ $link = url('password.reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
                <button style="background-color:#006dcc;vertical-align: bottom;text-align: center;color: white;padding: 10px;">
                    <b> Reset Password </b></button> </a>
            </p>
            <br>
            If you did not request a password reset, no further action is required.
            <br><br>
            Regards,
            <br>
            EWS Team
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md12 col-lg-12">
            <br><br><hr>
            <small>
                If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                <br>
                <a href="{{ $link = url('password.reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
                    {{ $link }} </a>
            </small>
        </div>

    </div>
</div>