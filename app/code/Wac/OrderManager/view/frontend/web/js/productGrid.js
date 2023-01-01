define([
    "jquery",
    'Magento_Ui/js/modal/confirm',
    "owlcarousel"
], ($,confirmation) => {
    'use strict';
    let productGrid = (url) => {

        $('#status_change_btn').click(function() {
            var values = $("input[name='product_id[]']:checked")
            .map(function(){return $(this).val();}).get();

            if(values.length==0){

                confirmation({
                    title: 'No Selected Items',
                    content: 'Please select items',
                    buttons: [ {
                        text: $.mage.__('OK'),
                        class: 'action-primary action-accept',
                        click: function (event) {
                            this.closeModal(event, true);
                        }
                    }]
                });
                return;
            }

            var NumberOfItmes = values.length;
            var StatusName = $('#status_action').find(":selected").text();


            confirmation({
                title: 'Update Items',
                content: 'Do you want to '+StatusName+'  '+NumberOfItmes+' Items?',
                actions: {

                    confirm: function () {
                        $.ajax({
                            url: url.statuschangeall,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                product_id: values,
                                store: url.store_code,
                                status_code: $('#status_action').find(":selected").val()
                            },
                            complete: function(response) {
                                if(response.responseJSON.error==true){
                                    alert(response.responseJSON.msg);

                                }

                                if(response.responseJSON.error==false){
                                   // alert(response.responseJSON.msg);
                                   location.reload();

                                }


                            },
                            error: function (xhr, status, errorThrown) {
                                console.log('Error happens. Try again.');
                            }


                        });
                    },

                    cancel: function () {
                        return false;
                    }
                }
            });

        });
        var init = () => {
            $('body').on('keyup', '#product_search', function () {
                var input, filter, tbody, tr, name, sku, i, txtValueSku , txtValueName;
                input = document.getElementById("product_search");
                filter = input.value.toUpperCase();
                tbody = document.getElementById("product_listing");
                tr = tbody.getElementsByClassName("data-row");
                for (i = 0; i < tr.length; i++) {
                    name = tr[i].getElementsByClassName("data-grid-cell-content")[0];
                    sku = tr[i].getElementsByClassName("data-grid-cell-content")[1];
                    txtValueName = name.textContent || name.innerText;
                    txtValueSku = sku.textContent || sku.innerText;

                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueSku.toUpperCase().indexOf(filter) > -1 ) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            });
        }

        /*
         * event: initializing all dom ready method and binding events
         */
        init();



    }
    return productGrid;
});
