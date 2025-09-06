<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="file">Upload File</label>
        <input type="file" name="file">
    </div>
    <button type="submit">Import</button>
</form>