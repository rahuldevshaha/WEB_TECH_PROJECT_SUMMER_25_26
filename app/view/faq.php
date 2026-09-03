<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include_once __DIR__ . "/../utils/securityValidation.php";
    ProtectedRequest("/app/controller/login/socialLogin.php");

    $faqList = [
        [
            "q" => "যেকোন প্রয়োজনে সরাসরি আপনাদের সাথে কিভাবে যোগাযোগ করবো?",
            "a" => "আমাদের সাথে সরাসরি যোগাযোগ করতে উপরের 'আমাদের সাথে যোগাযোগ করুন' বাটনে ক্লিক করে আমাদের অফিসিয়াল ফেসবুক পেজ বা হেল্পলাইনে মেসেজ পাঠাতে পারেন।"
        ],
        [
            "q" => "এপ কিভাবে ফ্রী ব্যবহার করতে পারবো?",
            "a" => "মেসের মূল ফিচারগুলো যেমন মিল গণনা, হিসাব ও খরচ ট্র্যাকিং সম্পূর্ণ বিনামূল্যে ব্যবহার করতে পারবেন।"
        ],
        [
            "q" => "প্রিমিয়াম মেম্বারশিপ কি?",
            "a" => "প্রিমিয়াম মেম্বারশিপ হল একটি বিশেষ ফিচার প্যাক যাতে আনলিমিটেড মেম্বার, বিস্তারিত রিপোর্ট ডাউনলোড এবং ক্লাউড ব্যাকআপের সুবিধা পাওয়া যায়।"
        ],
        [
            "q" => "প্রিমিয়াম মেম্বারশিপ এর সুবিধা কি?",
            "a" => "বিজ্ঞাপনমুক্ত অভিজ্ঞতা, দ্রুত ডাটা সিঙ্ক, স্বয়ংক্রিয় ব্যালেন্স ক্যালকুলেশন এবং মাল্টি-মেস পরিচালনার সুবিধা।"
        ],
        [
            "q" => "কেনো প্রিমিয়াম মেম্বারশিপ অপশন দেয়া হয়েছে এপ এ? কেন সম্পূর্ণ এপ পুরোপুরি ফ্রি নয়?",
            "a" => "সার্ভার পরিচালন খরচ, উন্নত ডেটা সুরক্ষা ও নিয়মিত নতুন আপডেট নিশ্চিত করার জন্য প্রিমিয়াম অপশনটি চালু রাখা হয়েছে।"
        ],
        [
            "q" => "প্রিমিয়াম মেম্বারশিপ কি সবাইকে নিতে হবে নাকি শুধু ম্যানেজার এর জন্যই যথেষ্ট?",
            "a" => "না, সবাইকে নিতে হবে না। মেস ম্যানেজারের একটি প্রিমিয়াম অ্যাকাউন্ট থাকলে পুরো মেসের সকল সদস্যই সুবিধা উপভোগ করতে পারবেন।"
        ],
        [
            "q" => "কোন রানিং মাসের মাঝখানে যদি কোন মেম্বার চলে যায় তাহলে হিসাব কিভাবে করতে হবে?",
            "a" => "মেম্বারের চলে যাওয়ার তারিখ পর্যন্ত মিল ও খরচ বন্ধ করে দিয়ে তার চূড়ান্ত ব্যালেন্স সমন্বয় করে নিতে পারেন।"
        ],
        [
            "q" => "যদি কোন ডাটা ভুল ইনপুট দেই তাহলে সেটা কি ডিলেট করা যাবে? যেমন: মিল, খরচ, ডিপোজিট?",
            "a" => "হ্যাঁ, ম্যানেজার তার ড্যাশবোর্ড বা বিস্তারিত হিসাব পেজ থেকে যেকোনো ভুল এন্ট্রি এডিট বা ডিলেট করতে পারবেন।"
        ],
        [
            "q" => "মাসের হিসাব কি 1 তারিখেই শেষ করতে হবে?",
            "a" => "না, প্রয়োজন অনুযায়ী মেসের হিসাব যেকোনো তারিখে ক্লোজ করে নতুন মাস শুরু করতে পারবেন।"
        ]
    ];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support & FAQ</title>
    <link rel="stylesheet" href="/app/assets/css/layoutFile.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .faq-page-container {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            padding: 30px 16px 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .faq-header-card {
            text-align: center;
            margin-bottom: 24px;
        }

        .faq-support-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: contain;
            margin: 0 auto 10px;
            display: block;
        }

        .faq-title-red {
            color: #ef4444;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .faq-sub-txt {
            color: #374151;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .btn-contact-black {
            display: inline-block;
            background-color: #111827;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 22px;
            border-radius: 8px;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .btn-contact-black:hover {
            opacity: 0.9;
        }

        .faq-section-label {
            color: #ef4444;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: center;
            margin: 20px 0 14px;
        }

        .faq-accordion-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .faq-accordion-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .faq-accordion-header {
            width: 100%;
            padding: 13px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            font-size: 13.5px;
            font-weight: 500;
            color: #1f2937;
        }

        .faq-accordion-header:hover {
            color: #ef4444;
        }

        .faq-accordion-chevron {
            font-size: 11px;
            color: #6b7280;
            transition: transform 0.2s ease;
        }

        .faq-accordion-item.open .faq-accordion-chevron {
            transform: rotate(180deg);
        }

        .faq-accordion-body {
            display: none;
            padding: 0 18px 14px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
        }

        .faq-accordion-item.open .faq-accordion-body {
            display: block;
        }

        .about-card-box {
            width: 100%;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 18px 20px;
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 9px;
            margin-top: 14px;
        }

        .about-item-link {
            text-decoration: none;
            color: #4b5563;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }

        .about-item-link:hover {
            color: #ef4444;
        }
    </style>
</head>
<body>

    
    <?php include __DIR__ . "/layout/navbar.php"; ?>

   
    <main class="faq-page-container">
        <div class="faq-header-card">
            <img src="/app/assets/images/messManagerLogo.png" alt="Support Logo" class="faq-support-icon" onerror="this.src='/app/assets/images/support.svg'">
            <h4 class="faq-title-red">Support</h4>
            <p class="faq-sub-txt">Facing Any Problem?</p>
            <a href="https://facebook.com" target="_blank" class="btn-contact-black">আমাদের সাথে যোগাযোগ করুন</a>
        </div>

        <h4 class="faq-section-label">FAQ</h4>

        <div class="faq-accordion-group">
            <?php foreach ($faqList as $faq): ?>
                <div class="faq-accordion-item">
                    <button type="button" class="faq-accordion-header">
                        <span><?php echo htmlspecialchars($faq['q']); ?></span>
                        <span class="faq-accordion-chevron">▼</span>
                    </button>
                    <div class="faq-accordion-body">
                        <p><?php echo htmlspecialchars($faq['a']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4 class="faq-section-label" style="margin-top: 28px;">About Us</h4>

        <div class="about-card-box">
            <a href="javascript:void(0);" class="about-item-link">📯 About Us</a>
            <a href="javascript:void(0);" class="about-item-link">📯 How to use this app</a>
            <a href="javascript:void(0);" class="about-item-link">📯 Rate Us/ Share your feedback</a>
            <a href="javascript:void(0);" class="about-item-link">📯 Privacy Policy</a>
            <a href="javascript:void(0);" class="about-item-link">📯 Terms & Condition</a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('.faq-accordion-item');
            items.forEach(item => {
                const header = item.querySelector('.faq-accordion-header');
                header.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');
                    items.forEach(i => i.classList.remove('open'));
                    if (!isOpen) {
                        item.classList.add('open');
                    }
                });
            });
        });
    </script>
</body>
</html>