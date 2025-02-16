/**
 * Copyright © MageForge - Free Modules. All rights reserved.
 * Developed by : MageForge - Free Modules
 * Contact : max.developperfb@gmail.com
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return function (config) {
        let options = {
            type: 'popup',
            title: 'Reindex Product',
            responsive: true,
            buttons: []
        };

        let popup = modal(options, $('#reindex-product-modal'));

        $('.reindex-product-button').click(function () {
            $.ajax({
                url: '/backend/mageforge_quickindex/product/getindexes',
                type: 'POST',
                showLoader: true,
                data: { form_key: window.FORM_KEY },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (response.success) {
                        let checkboxes = '';
                        response.indexes.forEach(function (index) {
                            checkboxes += `<label><input type="checkbox" name="index[]" value="${index.id}"> ${index.title}</label><br>`;
                        });

                        $('#reindex-checkboxes').html(checkboxes);
                        $('#reindex-product-modal').modal('openModal');
                    }
                },
                error: function () {
                    alert('Error loading indexes.');
                }
            });
        });

        $('#submit-reindex').click(function (e) {
            e.preventDefault();

            let selectedIndexes = [];
            $('input[name="index[]"]:checked').each(function () {
                selectedIndexes.push($(this).val());
            });

            if (selectedIndexes.length === 0) {
                alert('Please select at least one index.');
                return;
            }

            $.ajax({
                url: '/backend/mageforge_quickindex/product/reindex',
                type: 'POST',
                data: { indexes: selectedIndexes, product_id: config.product_id },
                showLoader: true,
                success: function (response) {
                    if (response.success) {
                        alert('Reindexing Completed Successfully.');
                    } else {
                        alert('Error: ' + response.message);
                    }
                    $('#reindex-product-modal').modal('closeModal');
                },
                error: function () {
                    alert('Error in reindexing.');
                }
            });
        });
    };
});
