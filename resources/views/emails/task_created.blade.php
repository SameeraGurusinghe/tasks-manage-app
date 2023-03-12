<!-- <p>Hello {{ $user->name }},</p>

<p>A new task has been created:</p>

<p>Title: {{ $task->title }}</p>
<p>Description: {{ $task->description }}</p>
<p>Due Date: {{ $task->due_date->format('F j, Y') }}</p>

<p>Regards,<br>
MyTasksManager</p> -->

<!DOCTYPE html>
<html>
<head>
    <title>New Task Created</title>
</head>
<body>
    <h2>New Task Created</h2>
    <p>Hello {{ $user->name }}, a new task has been created with the following details:</p>
    <ul>
        <li><b>Task Name:</b> {{ $task->title }}</li>
        <li><b>Task Description:</b> {{ $task->description }}</li>
        <li><b>Task Created Date:</b> {{ $task->created_at }}</li>
        <li><b>Task Due Date:</b> {{ $task->due_date }}</li>
    </ul>
    <p>You can view the task details by logging into your account.</p>
    <br>
    <p>Thanks,</p>
    <p>MyTasksManager</p>
</body>
</html>
