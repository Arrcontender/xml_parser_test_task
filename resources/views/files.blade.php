<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }
        th:hover {
            background-color: lightgrey;
        }
        td.file-name {
            cursor: pointer;
        }
        td.file-name:hover {
            background-color: lightgrey;
        }
        .upload {
            text-align: right;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>



<body>

<h2>Files</h2>

<form action="{{ route('index') }}" method="GET">
    <label for="name">Filter by Name:</label>
    <input type="text" id="name" name="name" value="{{ request('name') }}">
    <label for="created_at">Filter by Date:</label>
    <input type="date" id="created_at" name="created_at" value="{{ request('created_at') }}">
    <button type="submit">Filter</button>
</form>

<br>

@if(count($files) > 0)
    <table id="fileTable">
        <thead>
        <tr>
            <th data-index="0">ID</th>
            <th data-index="1">Name</th>
            <th data-index="2">Created At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td class="file-name">{{ $file->name }}</td>
                <td>{{ $file->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No files found.</p>
@endif

<div class="upload">
    <h4>Upload File</h4>
    @if ($errors->any())
        <div style="color: #ef4444">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div style="color: #ef4444">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
</div>

<script>
    $(document).ready(function(){
        $('th').click(function(){
            var table = $('#fileTable');
            var rows = table.find('tr:gt(0)').toArray().sort(compare($(this).data('index')));
            this.asc = !this.asc;
            if (!this.asc){ rows = rows.reverse(); }
            for (var i = 0; i < rows.length; i++){ table.append(rows[i]); }
        });
    });

    function compare(index) {
        return function(a, b) {
            var valA = $(a).find('td').eq(index).text().toUpperCase();
            var valB = $(b).find('td').eq(index).text().toUpperCase();
            return valA > valB ? 1 : valA < valB ? -1 : 0;
        }
    }

    $(document).ready(function(){
        function loadXMLContent(filename) {
            $.ajax({
                url: '/get-file',
                method: 'GET',
                data: { filename: filename },
                success: () => {
                    window.location.href = '/file?filename=' + filename;
                },
                error: function() {
                    alert('Failed to load XML content.');
                }
            });
        }

        $('td.file-name').click(function() {
            var filename = $(this).text();
            loadXMLContent(filename);
        });
    });
</script>

</body>
</html>
