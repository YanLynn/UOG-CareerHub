<!DOCTYPE html>
<html>
<head>
    <title>Job Approved</title>
</head>


<body>
  <h2 style="color:#4CAF50">🎉 Congratulations, {{ $application->jobseeker->user->name }}!</h2>
  <p>Your application for the <strong>{{ $application->job->job_title }}</strong> position at <strong>{{ $application->job->job_location }}</strong> has been <strong>approved</strong>.</p>
  <p>The employer will be in touch shortly with next steps. We wish you the best in your career journey!</p>
  <p style="margin-top:30px;">— Career Hub Team</p>
</body>


</html>
