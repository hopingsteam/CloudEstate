@extends('layouts.app')

@section('content')
    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">

        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start contact-page Area -->
    <section class="contact-page-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="alert alert-success" style="display: none" id="dialogSuccess">Property updated sucesfully!</div>
            <div class="section-top-border">
                <h3 class="mb-30">Property #{{ $propertyId }}</h3>
                <div class="row">
                    <div class="col-md-3">
                        <img src=" {{ asset("img/elements/d.jpg") }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-9 mt-sm-20">
                        <div id="table">
                            <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th scope="row">Description</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="descriptionInput">{{ $property['description'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Location</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="locationInput">{{ $property['location'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Area</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="areaInput">{{ $property['area'] }} sqm</td>
                            </tr>
                            <tr>
                                <th scope="row">Price</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="priceInput"><strong>£</strong> {{ $property['price'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Added on</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="addedOnInput">{{ $property['dateAdded'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Number of bedrooms</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="bedroomsInput">{{ $property['bedrooms'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Number of bathrooms</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="bathroomsInput">{{ $property['bathrooms'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Availability</th>
                                <td @if(session()->has('userId')) contenteditable="true" @endif id="availabilityInput">{{ $property['toRent'] == 1 ? "To Rent" : "For Sale" }}</td>
                            </tr>
                            @if(session()->has('userId'))
                                <tr>
                                    <th><button id="delete-btn-2" class="btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal">Delete Property</button></th>
                                    <td><button id="update-btn" class="btn-sm btn-info">Update Property</button></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <p id="export"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End contact-page Area -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to delete this property?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Description:</strong> {{ $property['description'] }} <br>
                    <strong>Location:</strong> {{ $property['location'] }} <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="delete-btn" type="button" class="btn btn-danger">DELETE</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('customJS')
    <script>
        function hideDialog() {
            var dialog = document.getElementById('dialogSuccess');
            dialog.style.display = "none";
        }

        var $BTN = $('#update-btn');
        $BTN.click(function () {
            var description =   document.getElementById('descriptionInput').innerText;
            var location =      document.getElementById('locationInput').innerText;
            var area =          document.getElementById('areaInput').innerText;
            var price =         document.getElementById('priceInput').innerText;
            var addedOn =       document.getElementById('addedOnInput').innerText;
            var bedrooms =      document.getElementById('bedroomsInput').innerText;
            var bathrooms =     document.getElementById('bathroomsInput').innerText;
            var availability =  document.getElementById('availabilityInput').innerText;
            var propertyId =    "@php echo $propertyId; @endphp"
            var dataToSend = {
                description : description,
                location : location,
                price : price,
                area : area,
                addedOn : addedOn,
                bedrooms : bedrooms,
                bathrooms : bathrooms,
                availability : availability,
                propertyId : propertyId
            }
            $.ajax({
                url: '/updateProperty',
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: dataToSend,
                success:function(response) {
                    var dialog = document.getElementById('dialogSuccess');
                    dialog.style.display = "block";

                    window.setTimeout(hideDialog, 5000);
                },
                error: function (xhr) {
                    alert(xhr.responseText);
                }
            });
        });
        
        var $DLT = $('#delete-btn');
        $DLT.click(function () {
            $.ajax({
                url: '/deleteProperty/@php echo $propertyId; @endphp',
                type: "DELETE",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success:function() {
                    window.location.replace("/properties");
                }
            });
        })
    </script>
@endsection