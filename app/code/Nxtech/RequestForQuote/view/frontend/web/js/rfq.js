define(["jquery", "Magento_Ui/js/modal/modal"], function ($, modal) {
    "use strict";
    return function (config, element) {
        var baseUrl = config.baseUrl;

        var options = {
            type: "popup",
            responsive: true,
            innerScroll: true,
            title: "Request for Quote",
            buttons: [
                {
                    text: $.mage.__("Close"),
                    class: "",
                    click: function () {
                        this.closeModal();
                    },
                },
            ],
        };

        var popup = modal(options, $("#rfq-modal"));

        $(".rfq-button").on("click", function () {
            $("#rfq-modal").modal("openModal");
        });

        $("#rfq-form").on("submit", function (e) {
            e.preventDefault();
            if ($("#rfq-form").valid()) {
                var dataForm = $("#rfq-form");
                /*dataForm.mage("validation", {});
                var formData = dataForm.serialize(); */

                var fd = new FormData($("#rfq-form")[0]);
                fd.append("file_attachment", true);

                $.ajax({
                    url: baseUrl,
                    data: fd,
                    showLoader: true,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var code = response.data.code;
                        var message = response.data.message;
                        if (code == 200) {
                            $(".action-close").trigger("click");
                            dataForm[0].reset();
                        } else {
                            alert(message);
                        }
                    },
                });
            }
        });
    };
});
