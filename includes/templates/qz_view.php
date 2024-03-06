<?php
$quiz_categories = get_option(PLUGIN_PREFIX . '_quiz_categories');
$quiz_cat_relation = get_option(PLUGIN_PREFIX . '_quiz_cat_relation');
?>
<section class="fullbody">
    <div class="body-container">
        <div class="bodyimg">
            <div class="img-btns">
                <div class="double-body-img">
                    <img src="<?php echo PLUGIN_DIR_URL . '/public/images/front-and-back-body.png'; ?>" alt="" class="fullbodyimage" />
                </div>
                <?php
                $count = 1;
                foreach ($quiz_categories as $key => $value) {
                    echo '<a href="javascript:void(0)" class="body-btn btn-' . $count . '" title="' . $value . '" data-key="' . $key . '" data-quiz_id="' . ((isset($quiz_cat_relation['category-' . $count]) && $quiz_cat_relation['category-' . $count] != '') ? $quiz_cat_relation['category-' . $count] : '') . '"></a>';
                    $count++;
                }
                ?>
            </div>
        </div>
        <div class="experiancing-pain">
            <h2 class="experience-head">Where Are You Experiencing Pain?</h2>
            <div class="experience-remove-content">
                <p class="content-1">
                    Take a look at the diagram consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
                <p class="content-2">
                    Select any body part ut perspiciatis unde omnis iste natus error
                    sit voluptatem accusantium doloremque laudantium, totam rem
                    aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                    architecto beatae vitae dicta sunt explicabo.
                </p>
                <p class="content-3">
                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur.
                </p>
            </div>
            <div class="step-form" style="display: none;">
                <h2 class="body-part-head"></h2>
                <form id="regForm" action="#" method="post">
                    <div style="overflow:auto;" class="nextPrev-btns-main">
                        <div style="float:right;" class="nextPrev-btns-sub">
                            <div class="prev-btn-box">
                                <!-- <button type="button" id="prevBtn" onclick="nextPrev(-1)">Back</button> -->
                            </div>
                            <div class="next-btn-box">
                                <!-- <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button> -->
                                <button type="button" id="nextBtn" class="btn_disabled">Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- Circles which indicates the steps of the form: -->
                    <div style="text-align:center;margin-top:40px;" class="step-dots">
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>