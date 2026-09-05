<link rel="stylesheet" href="/app/view/assets/css/successPopups.css">
<link rel="stylesheet" href="/app/view/assets/css/addMeal.css">
<link rel="stylesheet" href="/app/view/assets/css/home.css">


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


                    

                    <?php if(!empty($errorMessage)): ?>
                            <p class="status-msg error-status-msg">
                                <?php echo htmlspecialchars($errorMessage); ?>
                            </p>
                        <?php endif; ?>


                    

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


                            

                            <button
                                type="submit"
                                name="add_meal_submit"
                                class="primary-btn add-meal-top-btn"
                            >
                                Add Meal
                            </button>


                            

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


                        

                        <form
                            method="GET"
                            action=""
                            class="booked-filter-form"
                            id="bookedFilterForm"
                        >

                            

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


                            

                        </form>


                        

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


                    showInlineMealError('Meal settings save করা যায়নি। আবার চেষ্টা করুন।');

                }
            );

        }
    );

function showInlineMealError(message){
    let box = document.querySelector('.error-status-msg');
    if(!box){
        box = document.createElement('p');
        box.className = 'status-msg error-status-msg';
        const form = document.getElementById('setMealForm');
        if(form) form.prepend(box);
    }
    box.textContent = message;
}
</script>

<?php if (!empty($showSuccessModal)): ?><div class="success-popup"><div id="mealSuccessModal" class="modal-overlay active">
        <div class="modal-card">
            
            <div class="modal-illustration">
                <svg viewBox="0 0 240 200" width="200" height="170" xmlns="http://www.w3.org/2000/svg">
                    
                    <circle cx="120" cy="100" r="70" fill="#dcfce7" />
                    
                    
                    
                    <g transform="translate(60, 45)">
                        <rect x="0" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M 12 14 C 18 14, 22 10, 24 4 C 26 0, 29 2, 28 8 L 27 14 L 32 14 C 35 14, 35 18, 33 20 C 35 21, 35 24, 32 25 C 34 26, 33 29, 30 29 L 12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(155, 45) scale(-1, 1)">
                        <rect x="-24" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M -12 14 C -6 14, -2 10, 0 4 C 2 0, 5 2, 4 8 L 3 14 L 8 14 C 11 14, 11 18, 9 20 C 11 21, 11 24, 8 25 C 10 26, 9 29, 6 29 L -12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(62, 115)">
                        <rect x="0" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M 12 14 C 18 14, 22 10, 24 4 C 26 0, 29 2, 28 8 L 27 14 L 32 14 C 35 14, 35 18, 33 20 C 35 21, 35 24, 32 25 C 34 26, 33 29, 30 29 L 12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>
                    
                    <g transform="translate(155, 115) scale(-1, 1)">
                        <rect x="-24" y="8" width="12" height="18" fill="#4ade80" rx="2"/>
                        <path d="M -12 14 C -6 14, -2 10, 0 4 C 2 0, 5 2, 4 8 L 3 14 L 8 14 C 11 14, 11 18, 9 20 C 11 21, 11 24, 8 25 C 10 26, 9 29, 6 29 L -12 29 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="1"/>
                    </g>

                    
                    <ellipse cx="120" cy="180" rx="35" ry="7" fill="#e2e8f0" />

                    
                    <path d="M 112 110 L 118 110 L 115 175 L 108 175 Z" fill="#1e293b"/>
                    <path d="M 122 110 L 128 110 L 132 175 L 125 175 Z" fill="#1e293b"/>
                    <ellipse cx="106" cy="176" rx="6" ry="3" fill="#0f172a"/>
                    <ellipse cx="134" cy="176" rx="6" ry="3" fill="#0f172a"/>

                    
                    <polygon points="105,75 135,75 130,115 110,115" fill="#4ade80" />
                    <polygon points="118,78 122,78 123,108 117,108" fill="#eab308" />
                    <path d="M 105 78 L 94 95 L 102 98 L 110 85" fill="#4ade80"/>
                    <path d="M 135 78 L 146 95 L 138 98 L 130 85" fill="#4ade80"/>

                    
                    <circle cx="120" cy="62" r="10" fill="#fed7aa" />
                    <path d="M 112 60 Q 120 48 128 60 Q 126 52 115 54 Z" fill="#1e293b" />
                </svg>
            </div>

            
            <h2 class="modal-title"><?php echo htmlspecialchars($successMealTitle); ?></h2>
            <p class="modal-subtitle"><?php echo htmlspecialchars($successMealSubtitle); ?></p>

            
            <div class="modal-btn-row">
                <a href="activeMonthDetails.php" class="btn-outline">মাসের বিস্তারিত হিসাব</a>
                <a href="addMeal.php" class="btn-primary-red">Ok, Great!</a>
            </div>
        </div>
    </div></div><?php endif; ?>
