<h2>Hello {{ $details['name'] }},</h2>

<p>Your quiz has been scheduled. Below are your login and quiz details:</p>

<p><strong>Login ID:</strong> {{ $details['login_id'] }}</p>
<p><strong>Password:</strong> Reset Password</p>

<p><strong>Quiz:</strong> {{ $details['quiz_title'] }}</p>
<p><strong>Scheduled At:</strong> {{ $details['schedule_time'] }}</p>

<p><a href="{{ $details['quiz_link'] }}">Click here to join the quiz</a></p>
<p><a href="{{ $details['login_link'] }}">Login Here</a></p>

<p>Best of luck!</p>
