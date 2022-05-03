$(document).ready(function () {

    $("#countryForm").submit(function (event) {
        const countryName = $("#countryName").val();
        const searchFullName = $("#searchFullName").is(':checked') ? true : false;
        $('#resultData').remove();
        
        let endpoint = `http://localhost:8765/api/index.php?name=${countryName}&full-name=${searchFullName}&country-code=`
        $.ajax({
            url: endpoint,
            success: function(result){
                $('#result').append(`<div id="resultData"></div>`);

                $.each(result, function(key, value){
                    console.log(value);
                     $('#resultData').append(`<div id="resultDataElement"> 
                        <h3>Name: ${value.name.common}</h3>
                        Alpha Code 2: ${value.cca2} <br>
                        <br>
                     </div>`);
                });
            },
            error: function(result){ 
                if (result.status == 404) {
                   $('#result').append(`<div id="resultData">Search yielded no results <br></div>`);
                } 
                else {
                   throw result;
                }
            }
        })
      event.preventDefault();
    });

  });