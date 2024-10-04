<div class="form-group">
    <label for="name">Name:</label>
    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name', $product->name ?? '') }}">
</div>
<div class="form-group">
    <label for="description">Description:</label>
    <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $product->description ?? '') }}</textarea>
</div>
<div class="form-group">
    <label for="price">Price:</label>
    <input type="number" name="price" class="form-control" placeholder="Price" value="{{ old('price', $product->price ?? '') }}">
</div>
<div class="form-group">
    <label for="stock">Stock:</label>
    <input type="number" name="stock" class="form-control" placeholder="Stock" value="{{ old('stock', $product->stock ?? '') }}">
</div>
<div class="form-group">
    <label for="barcode">Barcode:</label>
    <input type="text" name="barcode" class="form-control" placeholder="Barcode" value="{{ old('barcode', $product->barcode ?? '') }}">
</div>
