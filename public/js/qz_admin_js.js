jQuery(document).ready(function ($) {

    /**
     * Questions JS 
     * 
     * */

    // Multiple options listing
    let prev_options = '';
    $('#question-type input').on('change', function () {
        let _this = $(this);
        let type = _this.val();

        if (type === 'multiple') {
            if (prev_options) {
                _this.parent().parent().append(prev_options);
            }
            else {
                _this.parent().parent().append(`
                    <div class="multiple-options">
                        <label>
                            <input type="text" name="multiple_options[]" value="" required />
                        </label>
                        <label>
                            <input type="text" name="multiple_options[]" value="" required />
                        </label>
                        <div class="add-question">
                            <a href="javascript:void(0)">Add New <i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                `);
            }
        }
        else {
            prev_options = _this.parent().parent().find('.multiple-options').clone();
            _this.parent().parent().find('.multiple-options').remove();
        }
    });

    // Add question
    $(document).on('click', '.add-question a', function () {
        let _this = $(this);

        if (_this.parent().parent().find('label').length < 6) {
            $(`<label>
                <input type="text" name="multiple_options[]" value="" required />
                <a href='javascript:void(0)' class="remove-question"><i class="fa fa-minus"></i></a>
            </label>`).insertBefore(_this.parent());
        }

        if (_this.parent().parent().find('label').length === 6) {
            _this.parent().remove();
        }
    });

    // Remove question
    $(document).on('click', '.remove-question', function () {
        let _this = $(this);

        if (_this.parent().parent().find('label').length <= 6 && _this.parent().parent().find('.add-question').length === 0) {
            _this.parent().parent().append(`<div class="add-question">
                                                <a href="javascript:void(0)">Add New <i class="fa fa-plus"></i></a>
                                            </div>`);
        }

        _this.parent().remove();
    });


    /**
     * Quiz JS 
     * 
     * */
});