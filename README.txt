An Uber-like web service. Allows a user to sign up to drive or ride in the service. Used a database and an available web api to allow a driver to search for riders within a certain geographical radius. 

Using the radius api:

Every time a user offers or requests a ride, the system will automatically check for a match. A driver offering a ride does not put in any destination city but rather a radius he is willing to drive, in km. When checking for a match, the system will take the offer radius and departure city postal code and use a free online API and searches for all postal codes within that radius. Then the system will see if the request city postal code is within the radius found by the API. In Canada the postal code narrows down to small areas, so instead we use the FSA (Forward Sortation Area) portion, which is the first 3 characters of the postal code, into consideration. The FSA represents portions of districts, which allows for the matching to be done more realistically instead of a pin point area. The request to the API is sent in a URL as a get request, and the response is received as a JSON encode. 

The API website is: http://www.geonames.org/. 
I had to make a free account to use this software. 
A request to the website looks like this: 
$url = "http://api.geonames.org/findNearbyPostalCodesJSON?postalcode=$postalCode&country=CA&radius=$radius&username=353super&maxRows=500";
