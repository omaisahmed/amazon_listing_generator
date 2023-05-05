$(document).ready(function () {

    // AJAX Form
    $('#product-form').submit(function (event) {
        event.preventDefault();
        var productName = $('#product-name').val();
        var productDescription = $('#product-description').val();
        $('#listing').empty();
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
                temperature: 0.5,
                max_tokens: 100,
                n: 5,
                stop: '\n'
            }),
            beforeSend: function() {
                $('#loader').addClass('spinner');
            },
            success: function (response) {
                if (response.choices && response.choices.length > 0) {
                    $.each(response.choices, function(index, choice) {
                        $('#listing').append('<p>' + choice.text + '</p>');
                    });
                }
                else {
                    $('#listing').append('<p>Sorry, I could not understand your request.</p>');
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