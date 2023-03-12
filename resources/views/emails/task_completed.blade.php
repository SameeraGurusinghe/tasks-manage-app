<!-- <p>Hello {{ $user->name }},</p>

<p>The following task has been completed:</p>

<p>Title: {{ $task->title }}</p>
<p>Description: {{ $task->description }}</p>

<p>Regards,<br>
MyTasksManager</p> -->

<!DOCTYPE html>
<html>
<head>
    <title>Task Completed</title>
</head>
<body>
    <h2>Task Completed</h2>
    <p>Hello {{ $user->name }}, the following task has been completed:</p>
    <ul>
        <li><b>Title:</b> {{ $task->title }}</li>
        <li><b>Task Description:</b> {{ $task->description }}</li>
        <li><b>Task Completed Date:</b> {{ $task->updated_at }}</li>
        <li><b>Task Due Date:</b> {{ $task->due_date }}</li>
    </ul>
    <p>You can view the task details by logging into your account.</p>
    <br>
    <p>Thanks,</p>
    <p>MyTasksManager</p>
</body>
</html>
