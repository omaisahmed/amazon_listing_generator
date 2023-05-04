@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div> --}}


                <form id="product-form">
                    <div class="form-group py-2">
                        <label for="product-name">Product Name:</label>
                        <input type="text" id="product-name" name="product_name" class="form-control">
                    </div>
                    <div class="form-group py-2">
                        <label for="product-description">Product Description:</label>
                        <textarea id="product-description" name="product_description" class="form-control" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Generate Listing</button>
                </form>
                <div id="listing"></div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#product-form').submit(function(event) {
                            event.preventDefault();
                            var productName = $('#product-name').val();
                            var productDescription = $('#product-description').val();
                            $.ajax({
                                url: '/generate-listing',
                                type: 'POST',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + '{{ env('OPENAI_API_KEY') }}'
                                },
                                data: JSON.stringify({
                                    product_name: productName,
                                    product_description: productDescription,
                                    temperature: 0.5,
                                    max_tokens: 100,
                                    n: 1,
                                    stop: '\n'
                                }),
                                success: function(response) {
                                    $('#listing').html('<p>' + response.choices[0].text + '</p>');
                                },
                                error: function(xhr) {
                                    console.log(xhr);
                                }
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </div>
@endsection
