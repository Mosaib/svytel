<form action="{{ route('data.import')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="file">Upload File</label>
        <input type="file" name="file" accept=".csv,.json">
    </div>
     <input type="hidden" name="model" value="App\Models\User">
    <button type="submit">Import</button>
</form>



<a class="btn btn-primary" href="{{ route('data.export', ['model' => 'User', 'format' => 'csv']) }}">CSV</a>
<a class="btn btn-primary" href="{{ route('data.export', ['model' => 'User', 'format' => 'json']) }}">JSON</a>
<a class="btn btn-primary" href="{{ route('data.export', ['model' => 'User', 'format' => 'xml']) }}">XML</a>