<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Content</title>
</head>
<body>
    <button onclick="goBack()">Go Back</button>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <h2>File Content</h2>
    <pre>{{ $xmlContent }}</pre>
</body>
</html>
