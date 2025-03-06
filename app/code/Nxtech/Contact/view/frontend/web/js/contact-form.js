define(["jquery"], function ($) {
    "use strict";

    return function (config, element) {
        $(element).on("submit", function (event) {
            event.preventDefault();

            var formData = {
                name: $(element).find("#name").val(),
                email: $(element).find("#email").val(),
                message: $(element).find("#message").val(),
            };

            $.ajax({
                url: config.url,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert(result.message);
                    } else {
                        alert("There was an error submitting the form");
                    }
                },
                error: function () {
                    alert("There was an error submitting the form");
                },
            });
        });
    };
});
