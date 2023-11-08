{{-- <select name="" id="">
    <option value="" selected hidden>Peran</option>
    <option value="Admin">Admin</option>
    <option value="Penghuni">Penghuni</option>
</select> --}}
@props(['option' => []])


<select {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    <option value="" selected hidden>Peran</option>
    <option value="Admin">Admin</option>
    <option value="Penghuni">Penghuni</option>
</select>
