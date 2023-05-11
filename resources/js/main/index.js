$(document).ready(function () {

    // AJAX Form
    $('#product-form').submit(function (event) {
        event.preventDefault();
        var productName = $('#product-name').val();
        var productDescription = $('#product-description').val();
        var length_limit = $('#length-limit').val();
        var description_length = $('#description-length').val();
        console.log(description_length);
        // var language = $('#language').val();
        // console.log(language);
        $('#product-names,#product-descriptions').empty();
        $('#loader').show();
        $.ajax({
            url: '/generate-listing',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + '{{ env("OPENAI_API_KEY") }}'
            },
            data: JSON.stringify({
                product_name: productName,
                product_description: productDescription,
                // language: language,
                temperature: 0.5,
                max_tokens: length_limit,         
                n: description_length,
                stop: '\n',
                top_p: 0.5,
                frequency_penalty: 0.5,
                presence_penalty: 0.5,               
            }),
            beforeSend: function() {
                $('#loader').addClass('spinner');
            },
            success: function (response) {
                if (response.product_names && response.product_names.choices && response.product_names.choices.length > 0) {                   
                    $.each(response.product_names.choices, function(index, choice) {
                        $('#product-names').append('<p>' + choice.text + '</p>');
                    });
                }
                else {
                    $('#product-names').append('<p>No product name suggestions available.</p>');
                }
            
                if (response.product_descriptions && response.product_descriptions.choices && response.product_descriptions.choices.length > 0) {
                    $('#product-descriptions').append('<h3>Description:</h3>');
                    $.each(response.product_descriptions.choices, function(index, choice) {
                        $('#product-descriptions').append('<p>' + choice.text + '</p>');
                    });
                }
                else {
                    $('#product-descriptions').append('<p>No product description suggestions available.</p>');
                }
            },
            complete: function() {
                $('#loader').removeClass('spinner');
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    // Current Year Footer
    const currentYear = new Date().getFullYear();
    document.getElementById('current-year').innerHTML = currentYear;

});