<?php 
/* foreach ($properties
    as $value) {
    print_r($value->price_per_night. " ********"); */
?>


<form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>
 