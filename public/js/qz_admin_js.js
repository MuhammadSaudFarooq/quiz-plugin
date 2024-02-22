jQuery(document).ready(function ($) {

    // Multiple options listing
    $('#question-type input').on('change', function () {
        let _this = $(this);
        let type = _this.val();

        if (type === 'multiple') {
            _this.parent().parent().append(`
                <div class="multiple-options">
                    <label>
                        <input type="text" name="multiple_options[]" value=""/>
                    </label>
                    <label>
                        <input type="text" name="multiple_options[]" value=""/>
                    </label>
                    <div class="add-new">
                        <a href="javascript:void(0)" class="add-new">Add New</a>
                    </div>
                </div>
            `);
        }
    });

    // Add new option
    $('.add-new').on('click', function () {
        
    });

});