<link rel="stylesheet" href="/app/assets/css/addMeal.css">
<link rel="stylesheet" href="/app/assets/css/home.css">


<div id="home_page">

    <div class="nav">
        <?php include __DIR__ . "/../layout/navbar.php"; ?>
    </div>


    <div class="body_section">

        <div class="sidebar_section">
            <?php include __DIR__ . "/../layout/sidebar.php"; ?>
        </div>


        <div class="content_section">

            <div id="content_body">

                <div class="main-container">


                    <!-- =========================================
                         STATUS MESSAGE
                    ========================================== -->

                    <?php if(!empty($errorMessage)): ?>

                        <p
                            class="status-msg"
                            style="color:#c0392b;"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errorMessage
                            );
                            ?>
                        </p>

                    <?php elseif(!empty($message)): ?>

                        <p class="status-msg">

                            <?php
                            echo htmlspecialchars(
                                $message
                            );
                            ?>

                        </p>

                    <?php endif; ?>


                    <!-- =========================================
                         BLOCKED NOTICE
                    ========================================== -->

                    <?php if(!empty($blockedNotices)): ?>

                        <div
                            class="notice-card"
                            style="
                                flex-direction:column;
                                align-items:flex-start;
                            "
                        >

                            <span class="notice-icon">
                                ⏰
                            </span>


                            <?php foreach(
                                $blockedNotices
                                as $notice
                            ): ?>

                                <span>
                                    <?php
                                    echo htmlspecialchars(
                                        $notice
                                    );
                                    ?>
                                </span>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>


                    <!-- =========================================
                         TABS
                    ========================================== -->

                    <div class="tab-box meal-page-tabs">

                        <button
                            type="button"
                            class="tab-btn <?php
                                echo (
                                    $activeMealTab === "add"
                                )
                                ? "active"
                                : "";
                            ?>"
                            onclick="
                                switchMealTab(
                                    'addMealSection',
                                    this
                                )
                            "
                        >
                            Add Meal
                        </button>


                        <button
                            type="button"
                            class="tab-btn <?php
                                echo (
                                    $activeMealTab === "booked"
                                )
                                ? "active"
                                : "";
                            ?>"
                            onclick="
                                switchMealTab(
                                    'bookedMealSection',
                                    this
                                )
                            "
                        >
                            Already Booked Meal
                        </button>

                    </div>


                    <!-- =========================================
                         ADD MEAL TAB
                    ========================================== -->

                    <div
                        id="addMealSection"
                        class="tab-content <?php
                            echo (
                                $activeMealTab === "add"
                            )
                            ? "active"
                            : "";
                        ?>"
                    >


                        <!--
                            IMPORTANT:
                            Remove current query string so
                            mealTab=booked does not remain after
                            Add Meal submit.
                        -->

                        <form
                            method="POST"
                            action="<?php
                                echo htmlspecialchars(
                                    strtok(
                                        $_SERVER["REQUEST_URI"],
                                        "?"
                                    )
                                );
                            ?>"
                        >


                            <!-- MEMBER -->

                            <div class="form-group">

                                <label>
                                    মেম্বার সিলেক্ট করুন
                                </label>


                                <div class="custom-select-wrapper">

                                    <?php if($isManager): ?>

                                        <select
                                            name="memberSelect"
                                            class="form-control"
                                        >

                                            <option value="all">
                                                For All Members
                                            </option>


                                            <?php foreach(
                                                $members
                                                as $m
                                            ): ?>

                                                <option
                                                    value="<?php
                                                        echo htmlspecialchars(
                                                            $m["userId"]
                                                        );
                                                    ?>"
                                                >

                                                    <?php
                                                    echo htmlspecialchars(
                                                        $m["Name"]
                                                    );
                                                    ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>


                                    <?php else: ?>


                                        <select
                                            class="form-control"
                                            disabled
                                        >

                                            <option
                                                value="<?php
                                                    echo htmlspecialchars(
                                                        $userId
                                                    );
                                                ?>"
                                            >
                                                নিজের জন্য (Only You)
                                            </option>

                                        </select>


                                        <input
                                            type="hidden"
                                            name="memberSelect"
                                            value="<?php
                                                echo htmlspecialchars(
                                                    $userId
                                                );
                                            ?>"
                                        >

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- DATE -->

                            <div class="form-group">

                                <label>
                                    মিলের তারিখ সিলেক্ট করুন
                                </label>


                                <input
                                    type="date"
                                    name="mealDate"
                                    class="form-control"
                                    value="<?php
                                        echo htmlspecialchars(
                                            $mealDate
                                        );
                                    ?>"
                                    required
                                >

                            </div>


                            <!-- SET MEAL BUTTON -->

                            <div class="section-label-row">

                                <div></div>


                                <button
                                    type="button"
                                    class="edit-icon-btn"
                                    onclick="openModal()"
                                    title="Edit Meal Values"
                                    aria-label="Edit Meal Values"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        width="20"
                                        height="20"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path
                                            d="
                                                M17 3
                                                a2.828 2.828 0 1 1 4 4
                                                L7.5 20.5
                                                2 22
                                                l1.5-5.5
                                                L17 3z
                                            "
                                        ></path>

                                    </svg>

                                </button>

                            </div>


                            <!-- =================================
                                 ADD MEAL BUTTON
                                 ABOVE TODAY'S MEALS
                            ================================== -->

                            <button
                                type="submit"
                                name="add_meal_submit"
                                class="primary-btn add-meal-top-btn"
                            >
                                Add Meal
                            </button>


                            <!-- =================================
                                 TODAY'S MEALS
                            ================================== -->

                            <div class="today-meal-card">

                                <div class="today-meal-card-head">

                                    <div>

                                        <h3>
                                            Today's Meals
                                        </h3>

                                        <p>
                                            <?php
                                            echo htmlspecialchars(
                                                date(
                                                    "d M Y",
                                                    strtotime(
                                                        $todayMealDate
                                                    )
                                                )
                                            );
                                            ?>
                                        </p>

                                    </div>


                                    <span class="today-count">

                                        <?php
                                        echo count($members);
                                        ?>

                                        member<?php
                                            echo (
                                                count($members) === 1
                                            )
                                            ? ""
                                            : "s";
                                        ?>

                                    </span>

                                </div>


                                <div class="today-meal-list">


                                    <?php if(empty($members)): ?>

                                        <div
                                            class="today-empty-state"
                                        >

                                            <div class="empty-icon">
                                                🍽
                                            </div>

                                            <strong>
                                                No members found
                                            </strong>

                                        </div>


                                    <?php else: ?>


                                        <?php foreach(
                                            $members
                                            as $member
                                        ): ?>


                                            <?php

                                            /*
                                             * Default all meals to 0.
                                             */

                                            $ms = array(
                                                "Name" =>
                                                    $member["Name"],

                                                "Morning" => 0,
                                                "Lunch"   => 0,
                                                "Dinner"  => 0
                                            );


                                            foreach(
                                                $mealsSetForDate
                                                as $mealRow
                                            ){

                                                if(
                                                    $mealRow["userId"]
                                                    ==
                                                    $member["userId"]
                                                ){

                                                    $ms = $mealRow;

                                                    break;
                                                }
                                            }

                                            ?>


                                            <div
                                                class="today-meal-row"
                                            >

                                                <div
                                                    class="
                                                        today-member-info
                                                    "
                                                >

                                                    <div
                                                        class="
                                                            today-avatar
                                                        "
                                                    >

                                                        <?php
                                                        echo htmlspecialchars(
                                                            strtoupper(
                                                                substr(
                                                                    trim(
                                                                        $ms["Name"]
                                                                    ),
                                                                    0,
                                                                    1
                                                                )
                                                            )
                                                        );
                                                        ?>

                                                    </div>


                                                    <div>

                                                        <div
                                                            class="
                                                                today-member-name
                                                            "
                                                        >
                                                            <?php
                                                            echo htmlspecialchars(
                                                                $ms["Name"]
                                                            );
                                                            ?>
                                                        </div>


                                                        <div
                                                            class="
                                                                today-member-label
                                                            "
                                                        >
                                                            Today's meal status
                                                        </div>

                                                    </div>

                                                </div>


                                                <div
                                                    class="
                                                        meal-code-list
                                                    "
                                                >

                                                    <span
                                                        class="
                                                            meal-code
                                                            breakfast-code
                                                        "
                                                    >
                                                        B:<?php
                                                        echo (int)ceil(
                                                            floatval(
                                                                $ms["Morning"]
                                                            )
                                                        );
                                                        ?>
                                                    </span>


                                                    <span
                                                        class="
                                                            meal-code
                                                            lunch-code
                                                        "
                                                    >
                                                        L:<?php
                                                        echo (int)ceil(
                                                            floatval(
                                                                $ms["Lunch"]
                                                            )
                                                        );
                                                        ?>
                                                    </span>


                                                    <span
                                                        class="
                                                            meal-code
                                                            dinner-code
                                                        "
                                                    >
                                                        D:<?php
                                                        echo (int)ceil(
                                                            floatval(
                                                                $ms["Dinner"]
                                                            )
                                                        );
                                                        ?>
                                                    </span>

                                                </div>

                                            </div>


                                        <?php endforeach; ?>


                                    <?php endif; ?>


                                </div>

                            </div>


                            <!-- LOCK NOTICE -->

                            <?php if(!$isManager):

                                list(
                                    $okM,
                                ) = canSetMeal(
                                    $mealDate,
                                    "Morning",
                                    $messRow,
                                    $isManager
                                );

                                list(
                                    $okL,
                                ) = canSetMeal(
                                    $mealDate,
                                    "Lunch",
                                    $messRow,
                                    $isManager
                                );

                                list(
                                    $okD,
                                ) = canSetMeal(
                                    $mealDate,
                                    "Dinner",
                                    $messRow,
                                    $isManager
                                );


                                if(
                                    !$okM ||
                                    !$okL ||
                                    !$okD
                                ):
                            ?>

                                <p
                                    style="
                                        font-size:12px;
                                        color:#888;
                                        margin-top:8px;
                                    "
                                >

                                    🔒 এই তারিখে

                                    <?php

                                    $locked = array();

                                    if(!$okM)
                                        $locked[] = "Breakfast";

                                    if(!$okL)
                                        $locked[] = "Lunch";

                                    if(!$okD)
                                        $locked[] = "Dinner";


                                    echo implode(
                                        ", ",
                                        $locked
                                    );

                                    ?>

                                    এর কুকিং টাইমের ৩ ঘণ্টার মধ্যে চলে এসেছে,
                                    তাই এখন শুধু ম্যানেজার এডিট করতে পারবে।

                                </p>

                            <?php
                                endif;
                            endif;
                            ?>


                            <!-- HIDDEN MEAL VALUES -->

                            <input
                                type="hidden"
                                name="breakfast_val"
                                value="<?php
                                    echo $breakfast;
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="lunch_val"
                                value="<?php
                                    echo $lunch;
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="dinner_val"
                                value="<?php
                                    echo $dinner;
                                ?>"
                            >


                        </form>

                    </div>


                    <!-- =========================================
                         ALREADY BOOKED MEAL TAB
                    ========================================== -->

                    <div
                        id="bookedMealSection"
                        class="tab-content <?php
                            echo (
                                $activeMealTab === "booked"
                            )
                            ? "active"
                            : "";
                        ?>"
                    >


                        <!-- =================================
                             AUTO FILTER
                             NO FILTER BUTTON
                        ================================== -->

                        <form
                            method="GET"
                            action=""
                            class="booked-filter-form"
                            id="bookedFilterForm"
                        >

                            <!-- Keep booked tab active -->

                            <input
                                type="hidden"
                                name="mealTab"
                                value="booked"
                            >


                            <div class="filter-group">

                                <label>
                                    Date
                                </label>

                                <input
                                    type="date"
                                    name="bookedDate"
                                    class="form-control"
                                    value="<?php
                                        echo htmlspecialchars(
                                            $bookedDateFilter
                                        );
                                    ?>"
                                >

                            </div>


                            <?php if($isManager): ?>

                                <div class="filter-group">

                                    <label>
                                        Person
                                    </label>


                                    <select
                                        name="bookedPerson"
                                        class="form-control"
                                    >

                                        <option value="">
                                            All Members
                                        </option>


                                        <?php foreach(
                                            $members
                                            as $m
                                        ): ?>

                                            <option
                                                value="<?php
                                                    echo htmlspecialchars(
                                                        $m["userId"]
                                                    );
                                                ?>"
                                                <?php
                                                echo (
                                                    $bookedPersonFilter
                                                    ===
                                                    $m["userId"]
                                                )
                                                ? "selected"
                                                : "";
                                                ?>
                                            >

                                                <?php
                                                echo htmlspecialchars(
                                                    $m["Name"]
                                                );
                                                ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            <?php else: ?>

                                <div class="filter-group">

                                    <label>
                                        Person
                                    </label>


                                    <select
                                        class="form-control"
                                        disabled
                                    >
                                        <option>
                                            নিজের জন্য (Only You)
                                        </option>
                                    </select>

                                </div>

                            <?php endif; ?>


                            <!-- NO FILTER BUTTON -->

                        </form>


                        <!-- =================================
                             BOOKED TABLE
                        ================================== -->

                        <div
                            class="
                                meal-table-card
                                booked-meal-card
                            "
                        >

                            <div
                                class="meal-table-wrap"
                            >

                                <table
                                    class="
                                        meal-table
                                        booked-meal-table
                                    "
                                >

                                    <thead>

                                        <tr>

                                            <th>
                                                Date
                                            </th>

                                            <th>
                                                Member
                                            </th>

                                            <th>
                                                Breakfast
                                            </th>

                                            <th>
                                                Lunch
                                            </th>

                                            <th>
                                                Dinner
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


<?php if(empty($alreadyBookedMeals)): ?>

                                        <tr>

                                            <td
                                                colspan="5"
                                                class="
                                                    empty-table-cell
                                                "
                                            >
                                                No meal found.
                                            </td>

                                        </tr>


<?php else: ?>


<?php

/*
 * Group all records by date.
 */

$groupedMeals = array();


foreach(
    $alreadyBookedMeals
    as $bm
){

    $date =
        $bm["mealDate"];


    if(
        !isset(
            $groupedMeals[$date]
        )
    ){

        $groupedMeals[$date] =
            array();
    }


    $groupedMeals[$date][] =
        $bm;
}


$currentToday =
    date("Y-m-d");


foreach(
    $groupedMeals
    as $mealDateValue => $dateMeals
){

    // -----------------------------
    // DATE STATUS
    // -----------------------------

    if(
        $mealDateValue < $currentToday
    ){

        $dateClass =
            "date-past";

        $dateStatus =
            "Past";

    }
    elseif(
        $mealDateValue ===
        $currentToday
    ){

        $dateClass =
            "date-today";

        $dateStatus =
            "Today";

    }
    else{

        $dateClass =
            "date-upcoming";

        $dateStatus =
            "Upcoming";
    }


    $rowspan =
        count($dateMeals);


    $firstRow =
        true;


    foreach(
        $dateMeals
        as $bm
    ):

?>

                                        <tr>


<?php if($firstRow): ?>

                                            <!--
                                                SAME DATE:
                                                ROWSPAN
                                            -->

                                            <td
                                                rowspan="<?php
                                                    echo $rowspan;
                                                ?>"
                                                class="
                                                    booked-date-cell
                                                    <?php
                                                    echo $dateClass;
                                                    ?>
                                                "
                                            >

                                                <div
                                                    class="
                                                        booked-date-main
                                                    "
                                                >

                                                    <?php
                                                    echo htmlspecialchars(
                                                        date(
                                                            "d M Y",
                                                            strtotime(
                                                                $mealDateValue
                                                            )
                                                        )
                                                    );
                                                    ?>

                                                </div>


                                                <span
                                                    class="
                                                        booked-date-status
                                                    "
                                                >
                                                    <?php
                                                    echo $dateStatus;
                                                    ?>
                                                </span>

                                            </td>

<?php endif; ?>


                                            <!-- MEMBER -->

                                            <td
                                                class="
                                                    booked-member-cell
                                                "
                                            >

                                                <div
                                                    class="
                                                        booked-member-wrap
                                                    "
                                                >

                                                    <div
                                                        class="
                                                            booked-member-avatar
                                                        "
                                                    >

                                                        <?php
                                                        echo htmlspecialchars(
                                                            strtoupper(
                                                                substr(
                                                                    trim(
                                                                        $bm["Name"]
                                                                    ),
                                                                    0,
                                                                    1
                                                                )
                                                            )
                                                        );
                                                        ?>

                                                    </div>


                                                    <span>
                                                        <?php
                                                        echo htmlspecialchars(
                                                            $bm["Name"]
                                                        );
                                                        ?>
                                                    </span>

                                                </div>

                                            </td>


                                            <!-- BREAKFAST -->

                                            <td>

<?php if(floatval($bm["Morning"]) > 0): ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        booked
                                                    "
                                                >
                                                    <?php
                                                    echo (float) $bm["Morning"];
                                                    ?>
                                                </span>

<?php else: ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        empty
                                                    "
                                                >
                                                    0
                                                </span>

<?php endif; ?>

                                            </td>


                                            <!-- LUNCH -->

                                            <td>

<?php if(floatval($bm["Lunch"]) > 0): ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        booked
                                                    "
                                                >
                                                    <?php
                                                    echo (float) $bm["Lunch"];
                                                    ?>
                                                </span>

<?php else: ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        empty
                                                    "
                                                >
                                                    0
                                                </span>

<?php endif; ?>

                                            </td>


                                            <!-- DINNER -->

                                            <td>

<?php if(floatval($bm["Dinner"]) > 0): ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        booked
                                                    "
                                                >
                                                    <?php
                                                    echo (float) $bm["Dinner"];
                                                    ?>
                                                </span>

<?php else: ?>

                                                <span
                                                    class="
                                                        meal-status
                                                        empty
                                                    "
                                                >
                                                    0
                                                </span>

<?php endif; ?>

                                            </td>


                                        </tr>


<?php

        $firstRow =
            false;

    endforeach;

}


endif;

?>


                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =========================================
                     SET MEAL MODAL
                ========================================== -->

                <div
                    id="setMealModal"
                    class="modal-overlay"
                >

                    <div class="modal-card">

                        <h2 class="modal-heading">
                            প্রতিদিনের মিল সেট করে রাখুন মেম্বারদের জন্য।
                        </h2>


                        <p class="modal-desc">
                            মিল তোলার সময় প্রয়োজনে এই সেট করা মিল পরিবর্তন করতে পারবেন।
                        </p>


                        <form
                            method="POST"
                            action=""
                            id="setMealForm"
                        >

                            <!--
                                IMPORTANT:
                                FormData(form) submit button
                                automatically নেয় না।
                            -->

                            <input
                                type="hidden"
                                name="ajax_set_meal"
                                value="1"
                            >

                            <input
                                type="hidden"
                                name="set_meal"
                                value="1"
                            >


                            <!-- BREAKFAST -->

                            <div
                                class="
                                    modal-input-group
                                    toggle-row
                                "
                            >

                                <label>
                                    Breakfast
                                </label>


                                <label class="switch">

                                    <input
                                        type="checkbox"
                                        name="modal_breakfast"
                                        value="1"
                                        <?php
                                        echo (
                                            $breakfast > 0
                                        )
                                        ? "checked"
                                        : "";
                                        ?>
                                    >

                                    <span
                                        class="
                                            slider
                                            round
                                        "
                                    ></span>

                                </label>

                            </div>


                            <!-- LUNCH -->

                            <div
                                class="
                                    modal-input-group
                                    toggle-row
                                "
                            >

                                <label>
                                    Lunch
                                </label>


                                <label class="switch">

                                    <input
                                        type="checkbox"
                                        name="modal_lunch"
                                        value="1"
                                        <?php
                                        echo (
                                            $lunch > 0
                                        )
                                        ? "checked"
                                        : "";
                                        ?>
                                    >

                                    <span
                                        class="
                                            slider
                                            round
                                        "
                                    ></span>

                                </label>

                            </div>


                            <!-- DINNER -->

                            <div
                                class="
                                    modal-input-group
                                    toggle-row
                                "
                            >

                                <label>
                                    Dinner
                                </label>


                                <label class="switch">

                                    <input
                                        type="checkbox"
                                        name="modal_dinner"
                                        value="1"
                                        <?php
                                        echo (
                                            $dinner > 0
                                        )
                                        ? "checked"
                                        : "";
                                        ?>
                                    >

                                    <span
                                        class="
                                            slider
                                            round
                                        "
                                    ></span>

                                </label>

                            </div>


                            <!-- BUTTONS -->

                            <div class="modal-btn-row">

                                <button
                                    type="button"
                                    class="cancel-btn"
                                    onclick="closeModal()"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="save-btn"
                                >
                                    Set Meal
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


            </div>


            <div class="footer_section">
                <?php
                include __DIR__ . "/../layout/footer.php";
                ?>
            </div>


        </div>

    </div>

</div>


<script>

/* =========================================================
   TAB SWITCH
========================================================= */

function switchMealTab(sectionId, button) {

    document
        .querySelectorAll(
            '.meal-page-tabs .tab-btn'
        )
        .forEach(function(btn) {

            btn.classList.remove(
                'active'
            );

        });


    document
        .querySelectorAll(
            '.meal-page-tabs ~ .tab-content'
        )
        .forEach(function(section) {

            section.classList.remove(
                'active'
            );

        });


    document
        .getElementById(sectionId)
        .classList.add(
            'active'
        );


    button.classList.add(
        'active'
    );


    /*
     * IMPORTANT:
     * Keep the URL's mealTab query param in sync with the
     * tab that's actually visible. Without this, switching
     * to "Add Meal" only changed CSS classes - the URL could
     * still carry a stale "?mealTab=booked" left over from
     * using the booked-tab filters earlier. A page reload
     * reads that stale param and forces the user back into
     * the Already Booked tab even though they were last on
     * Add Meal.
     */

    const url = new URL(window.location.href);

    if(sectionId === 'bookedMealSection'){

        url.searchParams.set('mealTab', 'booked');
    }
    else{

        url.searchParams.delete('mealTab');
    }

    window.history.replaceState(
        null,
        '',
        url.pathname + url.search
    );
}


/* =========================================================
   MODAL
========================================================= */

function openModal() {

    document
        .getElementById(
            'setMealModal'
        )
        .classList.add(
            'active'
        );
}


function closeModal() {

    document
        .getElementById(
            'setMealModal'
        )
        .classList.remove(
            'active'
        );
}


/* =========================================================
   AUTO FILTER
   Date / Member change => submit automatically
========================================================= */

(function(){

    const filterForm =
        document.getElementById(
            'bookedFilterForm'
        );


    if(!filterForm){

        return;
    }


    const dateInput =
        filterForm.querySelector(
            'input[name="bookedDate"]'
        );


    const personInput =
        filterForm.querySelector(
            'select[name="bookedPerson"]'
        );


    function autoFilter(){

        filterForm.submit();

    }


    if(dateInput){

        dateInput.addEventListener(
            'change',
            autoFilter
        );
    }


    if(personInput){

        personInput.addEventListener(
            'change',
            autoFilter
        );
    }

})();


/* =========================================================
   SET MEAL AJAX
========================================================= */

document
    .getElementById(
        'setMealForm'
    )
    .addEventListener(
        'submit',
        function(e){

            e.preventDefault();


            const form = this;


            const button =
                form.querySelector(
                    '.save-btn'
                );


            const originalText =
                button.textContent;


            button.disabled =
                true;


            button.textContent =
                'Saving...';


            fetch(
                window.location.href,
                {
                    method: 'POST',

                    body:
                        new FormData(form),

                    headers: {
                        'X-Requested-With':
                            'XMLHttpRequest'
                    }
                }
            )
            .then(
                response =>
                    response.json()
            )
            .then(
                data => {

                    if(!data.success){

                        throw new Error(
                            'Unable to save meal settings'
                        );
                    }


                    /*
                     * Update hidden values
                     * in Add Meal form.
                     */

                    const breakfastInput =
                        document.querySelector(
                            'input[name="breakfast_val"]'
                        );


                    const lunchInput =
                        document.querySelector(
                            'input[name="lunch_val"]'
                        );


                    const dinnerInput =
                        document.querySelector(
                            'input[name="dinner_val"]'
                        );


                    if(breakfastInput){

                        breakfastInput.value =
                            data.breakfast;
                    }


                    if(lunchInput){

                        lunchInput.value =
                            data.lunch;
                    }


                    if(dinnerInput){

                        dinnerInput.value =
                            data.dinner;
                    }


                    closeModal();


                    button.disabled =
                        false;


                    button.textContent =
                        originalText;

                }
            )
            .catch(
                function(){

                    button.disabled =
                        false;


                    button.textContent =
                        originalText;


                    alert(
                        'Meal settings save করা যায়নি। আবার চেষ্টা করুন।'
                    );

                }
            );

        }
    );

</script>