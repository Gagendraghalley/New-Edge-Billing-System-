<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reminder: Task Due Today</title>
</head>
<body>
    <h2>Reminder: Task Due Today</h2>
    <li><strong>Dear:</strong> {{ $task->title}}</li>
    <p>This is a reminder that the following task is due today:</p>
    
    <ul>
        <li><strong>Task:</strong> {{ $task->title}}</li>
        <li><strong>Description:</strong> {{ $task->description }}</li>
        <li><strong>Due Date:</strong> {{ $task->due_date }}</li>
    </ul>

    <p>Please make sure to complete the task before the end of the day.</p>

    <p>Thank you!</p>
</body>
</html>
