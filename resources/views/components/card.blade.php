{{--this wrapper class contains the card wrapper that will hold the the div that wraps around each job listing div. The tailwind classes here are bg(background-grey and border.)
<div class= "bg-gray-50 border border-gray-200 p-10 rounded">
    {{$slot}}
</div>
--}}
{{--Note that if we want to add some css customisation we don't have to touch this div wrapper directly, we can use a merge() function within laravel, which is like php's array_merge() meth but instead works to merge two collections' elements. 
Before we declare the intention of targetting the attirbutes laravel eloquent(ORM) accessor of this element. The accessors make an object-relational map of the html/DOM elements and then allow their access via accessor methods such as attribute casting???
By calling on the attributes placeholder for the __getAttributes magic method held within laravel's eloquent library of functions, we specify that we want to fetch the attributes of the object which will be making use of this html element(in this context the listings bladeview which is deploying this card wrapper.) The merge(method allows us to also merge not only this default collection that we specified in this card wrapper blade view (as a class of the div element to be injected) but also allows us to merge this collect ion wioth any further customisation that is being done on the recieving blade view- in this case the listings page... this saves time and effort since we don't have to update the css or other attirbtues from the wrapper class, and we can have a 'base case' wrapper class with a certain look while also customising each of its separate deployments) --}}
<div {{$attributes->merge(['class' => 'bg-gray-50 border border-gray-200 p-10 rounded'])}}>
    {{$slot}}
</div>
