<form action="{{ route('data.import')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="file">Upload File</label>
        <input type="file" name="file">
    </div>
     <input type="hidden" name="model" value="App\Models\User">
    <button type="submit">Import</button>
</form>