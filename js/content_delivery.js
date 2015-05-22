function show_revert_comments(lot_id, revertId, role){
        $.ajax({
            type: "POST",
            url: 'ajax/show_revert_comments.php',
            data: { lot_id: lot_id, revertId:revertId, role:role },
            success: function (msg) {
                if (msg) {
                    $.fancybox({
                        'content': msg,
                        'onCleanup': function () {
                            //
                        }

                    });
                }
            }
        });
    }


