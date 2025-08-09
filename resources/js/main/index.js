$(document).ready(function () {

    $('#product-form').submit(function (event) {
        event.preventDefault();

        const productName = $('#product-name').val();
        const productDescription = $('#product-description').val();
        const length_limit = $('#length-limit').val();
        const description_length = $('#description-length').val();

        $('#product-names, #product-descriptions').empty();
        $('#loader').show();

        $.ajax({
            url: '/generate-listing',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                product_name: productName,
                product_description: productDescription,
                length_limit: length_limit,          // ✅ matches Laravel
                description_length: description_length // ✅ matches Laravel
            }),
            contentType: 'application/json',
            success: function (response) {
                $('#product-names').empty();
                $('#product-descriptions').empty();

                // Add headings
                $('#product-names').append('<h4>Title Names</h4>');
                $('#product-descriptions').append('<h4>Description</h4>');

                // Show product names instantly
                if (Array.isArray(response.product_names) && response.product_names.length > 0) {
                    let nameList = $('<ul></ul>');
                    response.product_names.forEach(function (name) {
                        nameList.append('<li>' + name + '</li>');
                    });
                    $('#product-names').append(nameList);
                } else {
                    $('#product-names').append('<p>No product name suggestions available.</p>');
                }

                // Description typing effect
                let descriptionText = '';
                if (typeof response.product_description === 'string') {
                    descriptionText = response.product_description;
                }
                else if (response.product_description && Array.isArray(response.product_description.paragraphs)) {
                    descriptionText = response.product_description.paragraphs.join("\n\n");
                }
                else {
                    descriptionText = "No product description available.";
                }

                $('#product-descriptions').append('<pre id="full-typing"></pre>');
                typeWriterEffect($('#full-typing'), descriptionText.trim());

                $('#loader').hide();
            },
            complete: function () {
                $('#loader').hide();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                $('#loader').hide();
            }
        });

        function typeWriterEffect(element, text, speed = 20) {
            let i = 0;
            function typing() {
                if (i < text.length) {
                    element.append(text.charAt(i));
                    i++;
                    setTimeout(typing, speed);
                }
            }
            typing();
        }
    });

    // Current Year Footer
    $('#current-year').text(new Date().getFullYear());
});
